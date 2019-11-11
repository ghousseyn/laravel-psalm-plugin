<?php
namespace Psalm\LaravelPlugin;

use Orchestra\Testbench\Concerns\CreatesApplication;
use Psalm\LaravelPlugin\ReturnTypeProvider\AuthReturnTypeProvider;
use Psalm\LaravelPlugin\ReturnTypeProvider\TransReturnTypeProvider;
use Psalm\LaravelPlugin\ReturnTypeProvider\ViewReturnTypeProvider;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;
use SimpleXMLElement;

class Plugin implements PluginEntryPointInterface
{
    use CreatesApplication;

    /**
     * @return void
     */
    public function __invoke(RegistrationInterface $registration, ?SimpleXMLElement $config = null)
    {
        require_once 'ReturnTypeProvider/AuthReturnTypeProvider.php';
        $registration->registerHooksFromClass(AuthReturnTypeProvider::class);
        require_once 'ReturnTypeProvider/TransReturnTypeProvider.php';
        $registration->registerHooksFromClass(TransReturnTypeProvider::class);
        require_once 'ReturnTypeProvider/ViewReturnTypeProvider.php';
        $registration->registerHooksFromClass(ViewReturnTypeProvider::class);
        require_once 'AppInterfaceProvider.php';
        $registration->registerHooksFromClass(AppInterfaceProvider::class);
    }
    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        // ..
    }
}
