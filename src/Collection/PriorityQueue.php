<?php

namespace Ardent\Collection;

use Countable;
use IteratorAggregate;
use stdClass;

class PriorityQueue implements Countable, IteratorAggregate
{
    private $head;
    private $elements = [];

    /**
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * @param mixed $datum
     * @param float $priority
     */
    public function changePriority($datum, $priority)
    {
        if (!$this->contains($datum)) {
            return;
        }

        $node = $this->elements[hash($datum)];
        $oldPriority = $node->priority;
        $node->priority = $priority;

        if ($priority < $oldPriority) {
            $this->bubbleUp($node);
        } elseif ($priority > $oldPriority) {
            $this->percolateDown($node);
        }
    }

    /**
     * @param mixed $datum
     * @return bool
     */
    public function contains($datum)
    {
        return isset($this->elements[hash($datum)]);
    }

    /**
     * @param stdClass $node
     */
    private function bubbleUp(stdClass $node)
    {
        while ($node->parent && $node->priority < $node->parent->priority) {
            $parent = $node->parent;
            $grandparent = $parent ? $parent->parent : null;

            // Swap node and parent in place
            if ($parent) {
                $parent->parent = $node;
                $this->removeFromChildren($node, $parent);

                list($node->children, $parent->children) = [$parent->children, $node->children];

                $node->children[] = $parent;
            }

            // Swap node for parent in grandparent's collection of children.
            if ($grandparent) {
                $this->removeFromChildren($parent, $grandparent);
                $grandparent->children[] = $node;
            }

            $node->parent = $grandparent;
        }

        if (!$node->parent) {
            $this->head = $node;

            if ($this->head) {
                $this->head->parent = null;
            }
        }
    }

    /**
     * @param stdClass $child Node to be removed.
     * @param stdClass $parent Node with the child to remove.
     */
    private function removeFromChildren(stdClass $child, stdClass $parent)
    {
        foreach (array_keys($parent->children, $child, true) as $index) {
            array_splice($parent->children, $index, 1);
        }
    }

    /**
     * @param stdClass $node
     */
    private function percolateDown(stdClass $node)
    {
        $leastChild = array_reduce($node->children, function ($least, $child) {
            return (!$least || $child->priority < $least->priority) ? $child : $least;
        });

        if ($leastChild && $leastChild->priority < $node->priority) {
            $parent = $node->parent;

            // Swap node and leastChild in place
            $node->parent = $leastChild;
            $this->removeFromChildren($leastChild, $node);

            list($node->children, $leastChild->children) = [$leastChild->children, $node->children];

            $leastChild->children[] = $node;

            // Swap node for leastChild in parent's collection of children.
            if ($parent) {
                $this->removeFromChildren($node, $parent);
                $parent->children[] = $leastChild;
            }

            $leastChild->parent = $parent;

            $this->percolateDown($node);
        }
    }

    /**
     * @return mixed
     */
    public function dequeue()
    {
        assert(!$this->isEmpty());

        $datum = $this->head->datum;

        $this->head = $this->mergePairs($this->head->children);

        if ($this->head) {
            $this->head->parent = null;
        }

        unset($this->elements[hash($datum)]);

        return $datum;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return is_null($this->head);
    }

    /**
     * @param array $roots Array of Nodes that are the roots of their respective subtrees
     * @return stdClass|null The new subroot.
     */
    private function mergePairs(array $roots)
    {
        if (!count($roots)) {
            return null;
        }

        if (count($roots) === 1) {
            return $roots[0];
        }

        return $this->merge($this->merge($roots[0], $roots[1]), $this->mergePairs(array_slice($roots, 2)));
    }

    /**
     * @param stdClass|null $root
     * @param stdClass|null $otherRoot
     * @return stdClass|null
     */
    private function merge($root, $otherRoot)
    {
        if (!$root) {
            return $otherRoot;
        }

        if (!$otherRoot) {
            return $root;
        }

        if ($root->priority < $otherRoot->priority) {
            $root->children[] = $otherRoot;
            $otherRoot->parent = $root;

            return $root;
        }

        $otherRoot->children[] = $root;
        $root->parent = $otherRoot;

        return $otherRoot;
    }

    /**
     * @param mixed $datum
     * @param float $priority
     */
    public function enqueue($datum, $priority)
    {
        // Reinserting is a no-op
        if ($this->contains($datum)) {
            return;
        }

        $node = new stdClass;

        $node->children = [];
        $node->datum = $datum;
        $node->priority = $priority;

        $this->head = $this->merge($this->head, $node);

        if ($this->head) {
            $this->head->parent = null;
        }

        $this->elements[hash($datum)] = $node;
    }

    /**
     * @return PriorityQueueIterator
     */
    public function getIterator()
    {
        return new PriorityQueueIterator($this);
    }

    /**
     * @param mixed $datum
     */
    public function remove($datum)
    {
        if (!$this->contains($datum)) {
            return;
        }

        $node = $this->elements[hash($datum)];

        $parent = $node->parent;
        $rootChild = $this->mergePairs($node->children);

        if ($parent) {
            $this->removeFromChildren($node, $parent);

            if ($rootChild) {
                // Removed tree node
                $parent->children[] = $rootChild;
                $rootChild->parent = $parent;
            }
        } else {
            // Removed head
            $this->head = $rootChild;

            if ($this->head) {
                $this->head->parent = null;
            }
        }

        unset($this->elements[hash($datum)]);
    }
}
