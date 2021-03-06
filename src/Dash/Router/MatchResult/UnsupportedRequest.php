<?php
/**
 * Dash
 *
 * @link      http://github.com/DASPRiD/Dash For the canonical source repository
 * @copyright 2013 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Dash\Router\MatchResult;

/**
 * Match result returned when a router does not support the given request type.
 */
class UnsupportedRequest extends AbstractFailedMatch
{
    /**
     * @var string
     */
    protected $supportedRequestClassName;

    /**
     * @param string $supportedRequestClassName
     */
    public function __construct($supportedRequestClassName)
    {
        $this->supportedRequestClassName = $supportedRequestClassName;
    }

    /**
     * @return string
     */
    public function getSupportedRequestClassName()
    {
        return $this->supportedRequestClassName;
    }
}
