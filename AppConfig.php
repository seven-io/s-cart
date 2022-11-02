<?php
/**
 * Format plugin for S-Cart 3.x
 */
namespace App\Plugins\Other\Seven;

use App\Plugins\Other\Seven\Models\PluginModel;
use SCart\Core\Admin\Models\AdminConfig;
use App\Plugins\ConfigDefault;
use GuzzleHttp\Client;
use SCart\Core\Admin\Models\AdminMenu;

class AppConfig extends ConfigDefault {
    public function __construct() {
        $config = file_get_contents(__DIR__ . '/config.json'); // read config from config.json
        $config = json_decode($config, true);
        $this->configGroup = $config['configGroup'];
        $this->configCode = $config['configCode'];
        $this->configKey = $config['configKey'];
        $this->pathPlugin = $this->configGroup . '/' . $this->configCode . '/' . $this->configKey; //Path
        $this->title = trans($this->pathPlugin . '::lang.title'); //Language
        $this->image = $this->pathPlugin . '/' . $config['image']; //Image logo or thumb
        $this->version = $config['version'];
        $this->auth = $config['auth'];
        $this->link = $config['link'];
    }

    public function install() {
        if (AdminConfig::where('key', $this->configKey)->first()) {
            $error = 1;
            $msg = sc_language_render('plugin.plugin_action.plugin_exist');
            return compact('error', 'msg');
        }

        $error = 0;
        $msg = '';
        $menu = AdminMenu::where('key', $this->configKey)->first();

        if ($menu) $position = $menu->id;
        else {
            $menu = AdminMenu::create([
                'icon' => 'fas fa-tty',
                'key' => $this->configKey,
                'parent_id' => 25,
                'title' => $this->pathPlugin . '::lang.menu.heading',
            ]);
            $position = $menu->id;
        }

        AdminMenu::insert(
            [
                'icon' => 'far fa-envelope',
                'parent_id' => $position,
                'title' => $this->pathPlugin . '::lang.menu.bulk_sms',
                'uri' => 'route::admin_seven.index',
            ]
        );

        $dataInsert = [
            [
                'code' => $this->configCode,
                'detail' => $this->pathPlugin . '::lang.title',
                'group' => $this->configGroup,
                'key' => $this->configKey,
                'sort' => 0,
                'value' => self::ON, //Enable extension
            ],
            [
                'code' => $this->configKey . '_config',
                'detail' => $this->pathPlugin . '::lang.api_key',
                'group' => '',
                'key' => $this->configKey . '_api_key',
                'sort' => 0, // Sort extensions in group
                'value' => '',
            ],
        ];
        if (AdminConfig::insert($dataInsert)) {
            $pluginModel = new PluginModel;
            return $pluginModel->installExtension();
        } else {
            $error = 1;
            $msg = sc_language_render('plugin.plugin_action.install_faild');
        }

        return compact('error', 'msg');
    }

    public function uninstall() {
        $error = 0;
        $msg = '';

        $adminConfig = new AdminConfig;
        $process = $adminConfig
            ->where('key', $this->configKey)
            ->orWhere('code', $this->configKey . '_config')
            ->delete(); //Please delete all values inserted in the installation step

        if (!$process) {
            $error = 1;
            $msg = sc_language_render('plugin.plugin_action.action_error', ['action' => 'Uninstall']);
        }

        (new PluginModel)->uninstallExtension();

        (new AdminMenu)->where('uri', 'route::admin_seven.index')->delete();
        $checkMenu = (new AdminMenu)->where('key', $this->configKey)->first();
        if ($checkMenu) {
            if (!(new AdminMenu)->where('parent_id', $checkMenu->id)->count())
                (new AdminMenu)->where('key', $this->configKey)->delete();
        }

        return compact('error', 'msg');
    }

    public function enable() {
        $error = 0;
        $msg = '';

        $adminConfig = new AdminConfig;
        $process = $adminConfig
            ->where('key', $this->configKey)
            ->update(['value' => self::ON]);

        if (!$process) {
            $error = 1;
            $msg = 'Error enable';
        }

        return compact('error', 'msg');
    }

    public function disable() {
        $error = 0;
        $msg = '';

        $adminConfig = new AdminConfig;
        $process = $adminConfig
            ->where('key', $this->configKey)
            ->update(['value' => self::OFF]);

        if (!$process) {
            $error = 1;
            $msg = 'Error disable';
        }

        return compact('error', 'msg');
    }

    public function config() {
        $breadcrumb['url'] = sc_route_admin('admin_plugin', ['code' => $this->configCode]);
        $breadcrumb['name'] = sc_language_render('plugin.' . $this->configCode . '_plugin');

        return view($this->pathPlugin . '::Admin')->with([
            'breadcrumb' => $breadcrumb,
            'code' => $this->configCode,
            'key' => $this->configKey,
            'pathPlugin' => $this->pathPlugin,
            'title' => $this->title,
        ]);
    }

    public function getData() {
        return [
            'auth' => $this->auth,
            'code' => $this->configCode,
            'image' => $this->image,
            'key' => $this->configKey,
            'link' => $this->link,
            'pathPlugin' => $this->pathPlugin,
            'permission' => self::ALLOW,
            'title' => $this->title,
            'value' => 0, // this return need for plugin shipping
            'version' => $this->version,
        ];
    }

    /**
     * Process after order success
     * @param   [array]  $data
     */
    public function endApp($data = []) {
        //action after end app
    }
}
