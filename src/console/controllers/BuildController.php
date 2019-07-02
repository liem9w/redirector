<?php
/**
 * Redirector plugin for Craft CMS 3.x
 *
 * Automatically creates Retour static redirects using an entry dump.
 *
 * @link      https://justinjordan.io
 * @copyright Copyright (c) 2019 Justin Jordan
 */

namespace morsekode\redirector\console\controllers;

use morsekode\redirector\Redirector;

use Craft;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\Console;
use nystudio107\retour\services\Redirects;

/**
 * Build Command
 *
 * The first line of this class docblock is displayed as the description
 * of the Console Command in ./craft help
 *
 * Craft can be invoked via commandline console by using the `./craft` command
 * from the project root.
 *
 * Console Commands are just controllers that are invoked to handle console
 * actions. The segment routing is plugin-name/controller-name/action-name
 *
 * The actionIndex() method is what is executed if no sub-commands are supplied, e.g.:
 *
 * ./craft redirector/build
 *
 * Actions must be in 'kebab-case' so actionDoSomething() maps to 'do-something',
 * and would be invoked via:
 *
 * ./craft redirector/default/do-something
 *
 * @author    Justin Jordan
 * @package   Redirector
 * @since     0.1.0
 */
class BuildController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Builds redirects from JSON file
     * @param string  $redirectJsonPath  Path to JSON file containing redirects
     * @return int
     */
    public function actionIndex($redirectJsonPath): int
    {
        if (!class_exists(Redirects::class)) {
            throw new Exception("Retour plugin not detected");
        }

        if (!file_exists($redirectJsonPath)) {
            throw new Exception("Entry JSON file not found");
        }

        $redirectService = new Redirects();

        $redirects = json_decode(file_get_contents($redirectJsonPath));
        if (!is_array($redirects)) {
            throw new Exception("JSON should be an array of objects");
        }

        foreach ($redirects as $redirect) {
            if (!isset($redirect->id, $redirect->oldUri, $redirect->newUri)) {
                throw new Exception("Redirects should contain properties `id`, `oldUri`, and `newUri`");
            }

            $redirectService->saveRedirect([
                'associatedElementId'   => $redirect->id,
                'redirectSrcUrl'        => '/' . $redirect->oldUri,
                'redirectDestUrl'       => '/' . $redirect->newUri,
            ]);
        }

        Console::output(Console::renderColoredString("%YRedirects created!%n"));

        return 0;
    }
}
