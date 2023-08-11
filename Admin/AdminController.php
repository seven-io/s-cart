<?php
namespace App\Plugins\Other\Seven\Admin;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use SCart\Core\Admin\Controllers\RootAdminController;
use App\Plugins\Other\Seven\AppConfig;
use SCart\Core\Admin\Models\AdminCustomer;

class AdminController extends RootAdminController {
    public $plugin;

    public function __construct() {
        parent::__construct();

        $this->plugin = new AppConfig;
    }

    public function index() {
        return view($this->plugin->pathPlugin . '::Admin.index', (array)$this->plugin);
    }

    public function bulkSms(Request $request) {
        $debug = $request->boolean('seven_debug');
        $flash = $request->boolean('seven_flash');
        $foreign_id = $request->input('seven_foreign_id');
        $from = $request->input('seven_from');
        $label = $request->input('seven_label');
        $performance_tracking = $request->boolean('seven_performance_tracking');
        $text = $request->input('seven_text');
        $onlyEnabled = $request->boolean('seven_filter_only_enabled');

        $status = [1];
        if (!$onlyEnabled) $status[] = 0;

        $phoneCol = 'phone';
        $to = [];
        $query = AdminCustomer::query()
            ->where('store_id', '=', session('adminStoreId'))
            ->whereNotNull($phoneCol)
            ->where($phoneCol, '<>', '')
            ->whereIn('status', $status)
        ;

        foreach ($query->get()->getIterator() as $customer) {
            /** @var AdminCustomer $customer */
            $phone =  $customer->getAttribute('phone');
            $to[] = $phone;
        }

        $to = array_unique($to);
        $to = implode(',', $to);
        $params = compact(
            'debug',
            'flash',
            'foreign_id',
            'from',
            'label',
            'performance_tracking',
            'text',
            'to',
        );
        $params = array_merge($params, ['json' => 1]);
        $response = $this->apiCall('POST', 'sms', $params);
        $response = $response instanceof \Exception
            ? $response->getMessage()
            : json_encode($response, JSON_PRETTY_PRINT);
        $data = array_merge((array)$this->plugin, compact('response'));

        return view($this->plugin->pathPlugin . '::Admin.index', $data);
    }

    private function apiCall(string $method, string $endpoint, array $formParams = []) {
        try {
            $uri = 'https://gateway.seven.io/api/' . $endpoint;
            $res = (new Client)->request($method, $uri, [
                'headers' => [
                    'SentWith' => 'S-Cart',
                    'X-Api-Key' => $this->getApiKey(),
                ],
                'form_params' => $formParams,
            ]);
            return json_decode((string)$res->getBody());
        } catch (GuzzleException $e) {
            return $e;
        }
    }

    private function getApiKey() {
        return sc_config($this->plugin->configKey . '_api_key');
    }
}
