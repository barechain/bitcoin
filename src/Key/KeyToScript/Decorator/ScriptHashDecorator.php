<?php

declare(strict_types=1);

namespace Barechain\Bitcoin\Key\KeyToScript\Decorator;

use Barechain\Bitcoin\Exceptions\DisallowedScriptDataFactoryException;
use Barechain\Bitcoin\Key\KeyToScript\Factory\KeyToScriptDataFactory;
use Barechain\Bitcoin\Key\KeyToScript\ScriptDataFactory;

abstract class ScriptHashDecorator extends ScriptDataFactory
{
    /**
     * @var KeyToScriptDataFactory
     */
    protected $scriptDataFactory;

    /**
     * @var string[]
     */
    protected $allowedScriptTypes = [];

    /**
     * @var string
     */
    protected $decorateType;

    public function __construct(KeyToScriptDataFactory $scriptDataFactory)
    {
        if (!in_array($scriptDataFactory->getScriptType(), $this->allowedScriptTypes, true)) {
            throw new DisallowedScriptDataFactoryException("Unsupported key-to-script factory for this script-hash type.");
        }
        $this->scriptDataFactory = $scriptDataFactory;
    }

    /**
     * @return string
     */
    public function getScriptType(): string
    {
        return sprintf("%s|%s", $this->decorateType, $this->scriptDataFactory->getScriptType());
    }
}
