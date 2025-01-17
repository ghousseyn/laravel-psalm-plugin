<?php

namespace Psalm\LumenPlugin;

use PhpParser;
use Psalm\Context;
use Psalm\CodeLocation;
use Psalm\Type;
use Psalm\StatementsSource;

class AppInterfaceProvider implements
    \Psalm\Plugin\Hook\MethodReturnTypeProviderInterface,
    \Psalm\Plugin\Hook\MethodExistenceProviderInterface,
    \Psalm\Plugin\Hook\MethodVisibilityProviderInterface,
    \Psalm\Plugin\Hook\MethodParamsProviderInterface,
    \Psalm\Plugin\Hook\PropertyTypeProviderInterface
{
    public static function getPropertyType(string $fq_classlike_name, string $property_name, bool $read_mode, StatementsSource $source = null, Context $context = null)
    {
        if (preg_match('/^Illuminate/i', $fq_classlike_name) && $property_name === 'app') {
            return \Laravel\Lumen\Application::class;
        }
    }

    public static function getClassLikeNames() : array
    {
        return [
            \Illuminate\Contracts\Foundation\Application::class,
            \Illuminate\Contracts\Container\Container::class,
        ];
    }

    /**
     * @return ?bool
     */
    public static function doesMethodExist(
        string $fq_classlike_name,
        string $method_name_lowercase,
        StatementsSource $source = null,
        CodeLocation $code_location = null
    ) {
        if ($method_name_lowercase === 'offsetget'
            || $method_name_lowercase === 'offsetset'
        ) {
            return true;
        }
    }

    /**
     * @return ?bool
     */
    public static function isMethodVisible(
        StatementsSource $source,
        string $fq_classlike_name,
        string $method_name_lowercase,
        Context $context,
        CodeLocation $code_location = null
    ) {
        if ($method_name_lowercase === 'offsetget'
            || $method_name_lowercase === 'offsetset'
        ) {
            return true;
        }
    }

    /**
     * @param  array<PhpParser\Node\Arg>    $call_args
     * @return ?array<int, \Psalm\Storage\FunctionLikeParameter>
     */
    public static function getMethodParams(
        string $fq_classlike_name,
        string $method_name_lowercase,
        array $call_args = null,
        StatementsSource $statements_source = null,
        Context $context = null,
        CodeLocation $code_location = null
    ) {
        if ($statements_source) {
            if ($method_name_lowercase === 'offsetget' || $method_name_lowercase === 'offsetset') {
                return $statements_source->getCodebase()->getMethodParams(
                    \Laravel\Lumen\Application::class . '::' . $method_name_lowercase
                );
            }
        }
    }

    /**
     * @param  array<PhpParser\Node\Arg>    $call_args
     * @return  ?Type\Union
     */
    public static function getMethodReturnType(
        StatementsSource $source,
        string $fq_classlike_name,
        string $method_name_lowercase,
        array $call_args,
        Context $context,
        CodeLocation $code_location,
        array $template_type_parameters = null,
        string $called_fq_classlike_name = null,
        string $called_method_name_lowercase = null
    ) {
        if ($source) {
            if ($method_name_lowercase === 'offsetget' || $method_name_lowercase === 'offsetset') {
                return $source->getCodebase()->getMethodReturnType(
                    \Laravel\Lumen\Application::class . '::' . $method_name_lowercase,
                    $fq_classlike_name
                );
            }
        }
    }
}
