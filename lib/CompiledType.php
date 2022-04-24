<?php

namespace FFIMe;

class CompiledType
{
    public string $value;
    public string $rawValue;
    /** @var int[]|null[]|false[] */
    public array $indirections;
    public bool $isNative;

    /** @param int[]|null[]|false[] $indirections */
    public function __construct(string $value, array $indirections = [], ?string $rawValue = null) {
        $this->value = $value;
        $this->indirections = $indirections;
        $this->rawValue = $rawValue ?? $value;
        $this->isNative = !$indirections && $this->baseTypeIsNative();
        $this->functionArgs = [];
    }

    public function indirections() {
        return \count($this->indirections);
    }

    public function toValue(?string $compiledBaseType = null) {
        $value = $compiledBaseType ?? $this->rawValue;
        foreach ($this->indirections as $indirection) {
            if ($indirection === null || $indirection === false) {
                $value .= '*';
            } else {
                $value .= "[$indirection]";
            }
        }
        return $value;
    }

    public function baseTypeIsNative() {
        return $this->functionArgs === [] && ($this->value === 'int' || $this->value === 'float');
    }
}