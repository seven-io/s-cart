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

        $to = [];
        foreach (AdminCustomer::all()->getIterator() as $customer) {
            /** @var AdminCustomer $customer */
            $phone =  $customer->getAttribute('phone');
            if ($phone && $phone !== '') $to[] = $phone;
        }
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
        $body = null;
        $uri = 'https://gateway.sms77.io/api/' . $endpoint;
        $client = new Client();
        try {
            $res = $client->request($method, $uri, [
                'headers' => [
                    'SentWith' => 'S-Cart',
                    'X-Api-Key' => sc_config($this->plugin->configKey . '_api_key'),
                ],
                'form_params' => $formParams,
            ]);
            $body = json_decode((string)$res->getBody());
        } catch (GuzzleException $e) {
           return $e;
        }
        return $body;
    }
}
