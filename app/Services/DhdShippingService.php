<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DhdShippingService
{
    const STATUS_MAP = [
        'registered'           => 'confirmed',
        'En attente'           => 'processing',
        'En cours de livraison' => 'out_for_delivery',
        'Livré'                => 'delivered',
        'Retour transféré'     => 'returned',
        'Retour au bureau'     => 'returned',
        'Annulé'               => 'cancelled',
        'Refusé'               => 'returned',
    ];

    const WILAYAS = [
        ['id' => 1, 'name' => 'أدرار', 'price' => 800],
        ['id' => 2, 'name' => 'الشلف', 'price' => 700],
        ['id' => 3, 'name' => 'الأغواط', 'price' => 750],
        ['id' => 4, 'name' => 'أم البواقي', 'price' => 700],
        ['id' => 5, 'name' => 'باتنة', 'price' => 750],
        ['id' => 6, 'name' => 'بجاية', 'price' => 650],
        ['id' => 7, 'name' => 'بسكرة', 'price' => 800],
        ['id' => 8, 'name' => 'بشار', 'price' => 900],
        ['id' => 9, 'name' => 'البليدة', 'price' => 500],
        ['id' => 10, 'name' => 'البويرة', 'price' => 600],
        ['id' => 11, 'name' => 'تمنراست', 'price' => 1000],
        ['id' => 12, 'name' => 'تبسة', 'price' => 800],
        ['id' => 13, 'name' => 'تلمسان', 'price' => 700],
        ['id' => 14, 'name' => 'تيارت', 'price' => 700],
        ['id' => 15, 'name' => 'تيزي وزو', 'price' => 550],
        ['id' => 16, 'name' => 'الجزائر', 'price' => 400],
        ['id' => 17, 'name' => 'الجلفة', 'price' => 750],
        ['id' => 18, 'name' => 'جيجل', 'price' => 650],
        ['id' => 19, 'name' => 'سطيف', 'price' => 650],
        ['id' => 20, 'name' => 'سعيدة', 'price' => 700],
        ['id' => 21, 'name' => 'سكيكدة', 'price' => 650],
        ['id' => 22, 'name' => 'سيدي بلعباس', 'price' => 750],
        ['id' => 23, 'name' => 'عنابة', 'price' => 650],
        ['id' => 24, 'name' => 'قالمة', 'price' => 700],
        ['id' => 25, 'name' => 'قسنطينة', 'price' => 650],
        ['id' => 26, 'name' => 'المدية', 'price' => 550],
        ['id' => 27, 'name' => 'مستغانم', 'price' => 700],
        ['id' => 28, 'name' => 'المسيلة', 'price' => 700],
        ['id' => 29, 'name' => 'معسكر', 'price' => 750],
        ['id' => 30, 'name' => 'ورقلة', 'price' => 900],
        ['id' => 31, 'name' => 'وهران', 'price' => 600],
        ['id' => 32, 'name' => 'البيض', 'price' => 850],
        ['id' => 33, 'name' => 'إليزي', 'price' => 950],
        ['id' => 34, 'name' => 'برج بوعريريج', 'price' => 650],
        ['id' => 35, 'name' => 'بومرداس', 'price' => 500],
        ['id' => 36, 'name' => 'الطارف', 'price' => 650],
        ['id' => 37, 'name' => 'تندوف', 'price' => 1000],
        ['id' => 38, 'name' => 'تيسمسيلت', 'price' => 700],
        ['id' => 39, 'name' => 'الوادي', 'price' => 800],
        ['id' => 40, 'name' => 'خنشلة', 'price' => 750],
        ['id' => 41, 'name' => 'سوق أهراس', 'price' => 700],
        ['id' => 42, 'name' => 'تيبازة', 'price' => 500],
        ['id' => 43, 'name' => 'ميلة', 'price' => 650],
        ['id' => 44, 'name' => 'عين الدفلى', 'price' => 600],
        ['id' => 45, 'name' => 'النعامة', 'price' => 800],
        ['id' => 46, 'name' => 'عين تموشنت', 'price' => 650],
        ['id' => 47, 'name' => 'غرداية', 'price' => 900],
        ['id' => 48, 'name' => 'غليزان', 'price' => 700],
        ['id' => 49, 'name' => 'تيميمون', 'price' => 950],
        ['id' => 50, 'name' => 'برج باجي مختار', 'price' => 1100],
        ['id' => 51, 'name' => 'أولاد جلال', 'price' => 900],
        ['id' => 52, 'name' => 'بني عباس', 'price' => 950],
        ['id' => 53, 'name' => 'عين صالح', 'price' => 1000],
        ['id' => 54, 'name' => 'عين قزام', 'price' => 1100],
        ['id' => 55, 'name' => 'توقرت', 'price' => 900],
        ['id' => 56, 'name' => 'جانت', 'price' => 1050],
        ['id' => 57, 'name' => 'المغير', 'price' => 900],
        ['id' => 58, 'name' => 'المنيعة', 'price' => 950],
    ];

    protected ?int $shopId;
    protected array $settings = [];
    protected ?string $token = null;
    protected ?string $baseUrl = null;
    protected bool $mockMode = false;

    public function __construct(?int $shopId = null)
    {
        $this->shopId = $shopId;

        if ($shopId) {
            $this->loadSettings();
        }
    }

    protected function loadSettings(): void
    {
        $this->token = Setting::get($this->shopId, 'dhd_token');
        $this->baseUrl = rtrim(Setting::get($this->shopId, 'dhd_api_url') ?? '', '/');

        $this->settings = [
            'api_url'        => $this->baseUrl,
            'token'          => $this->token,
            'default_weight' => (int) (Setting::get($this->shopId, 'dhd_default_weight') ?: 1000),
            'can_open'       => Setting::get($this->shopId, 'dhd_can_open') !== '0',
            'is_fragile'     => Setting::get($this->shopId, 'dhd_is_fragile') === '1',
            'auto_send'      => Setting::get($this->shopId, 'dhd_auto_send') === '1',
        ];

        $this->mockMode = empty($this->token);
    }

    public function isMock(): bool
    {
        return $this->mockMode;
    }

    public function getConfig(): array
    {
        return [
            'api_url'        => $this->settings['api_url'] ?? null,
            'token'          => $this->token ? substr($this->token, 0, 8) . '...' . substr($this->token, -4) : null,
            'mock_mode'      => $this->mockMode,
            'default_weight' => $this->settings['default_weight'] ?? 1000,
            'can_open'       => $this->settings['can_open'] ?? true,
            'is_fragile'     => $this->settings['is_fragile'] ?? false,
            'auto_send'      => $this->settings['auto_send'] ?? false,
        ];
    }

    public function testConnection(): array
    {
        if ($this->mockMode) {
            return ['success' => true, 'message' => 'وضع المحاكاة نشط'];
        }

        try {
            $response = Http::withToken($this->token)
                ->timeout(15)
                ->acceptJson()
                ->withoutVerifying()
                ->get($this->baseUrl . '/api/v1/get/wilayas');

            if ($response->successful()) {
                $data = $response->json();
                $wilayas = is_array($data) ? $data : ($data['wilayas'] ?? $data['data'] ?? []);
                return [
                    'success' => true,
                    'message' => 'تم الاتصال بنجاح - ' . count($wilayas) ?? 58 . ' ولاية',
                ];
            }

            return [
                'success' => false,
                'message' => 'فشل الاتصال: HTTP ' . $response->status(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'خطأ: ' . $e->getMessage(),
            ];
        }
    }

    const WILAYA_NAME_TO_ID = [
        'Adrar' => 1, 'Chlef' => 2, 'Laghouat' => 3, 'Oum El Bouaghi' => 4, 'Batna' => 5,
        'Béjaïa' => 6, 'Biskra' => 7, 'Béchar' => 8, 'Blida' => 9, 'Bouira' => 10,
        'Tamanrasset' => 11, 'Tébessa' => 12, 'Tlemcen' => 13, 'Tiaret' => 14, 'Tizi Ouzou' => 15,
        'Alger' => 16, 'Djelfa' => 17, 'Jijel' => 18, 'Sétif' => 19, 'Saïda' => 20,
        'Skikda' => 21, 'Sidi Bel Abbès' => 22, 'Annaba' => 23, 'Guelma' => 24, 'Constantine' => 25,
        'Médéa' => 26, 'Mostaganem' => 27, "M'Sila" => 28, 'Mascara' => 29, 'Ouargla' => 30,
        'Oran' => 31, 'El Bayadh' => 32, 'Illizi' => 33, 'Bordj Bou Arreridj' => 34, 'Boumerdès' => 35,
        'El Tarf' => 36, 'Tindouf' => 37, 'Tissemsilt' => 38, 'El Oued' => 39, 'Khenchela' => 40,
        'Souk Ahras' => 41, 'Tipaza' => 42, 'Mila' => 43, 'Aïn Defla' => 44, 'Naâma' => 45,
        "Aïn Témouchent" => 46, 'Ghardaïa' => 47, 'Relizane' => 48, 'Timimoun' => 49,
        'Bordj Badji Mokhtar' => 50, 'Ouled Djellal' => 51, 'Beni Abbes' => 52, 'In Salah' => 53,
        'In Guezzam' => 54, 'Touggourt' => 55, 'Djanet' => 56, "El M'Ghair" => 57, 'El Meniaa' => 58,
    ];

    public function getCommunes(int $wilayaId): array
    {
        if ($this->mockMode) {
            return $this->mockCommunes($wilayaId);
        }

        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->acceptJson()
                ->withoutVerifying()
                ->get($this->baseUrl . '/api/v1/get/communes?wilaya_id=' . $wilayaId);

            if ($response->successful()) {
                $data = $response->json();
                $communes = is_array($data) ? $data : ($data['communes'] ?? $data['data'] ?? []);
                $names = array_map(fn($c) => $c['nom'] ?? $c, $communes);
                sort($names);
                return [
                    'success' => true,
                    'data'    => $names,
                    'raw'     => $communes,
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to fetch communes: HTTP ' . $response->status(),
                'data'    => [],
            ];
        } catch (\Exception $e) {
            Log::error('DHD getCommunes exception', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
                'data'    => [],
            ];
        }
    }

    public function getAllFees(): array
    {
        if ($this->mockMode) {
            return ['success' => true, 'data' => []];
        }

        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->acceptJson()
                ->withoutVerifying()
                ->get($this->baseUrl . '/api/v1/get/fees?wilaya_id=16');

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'data'    => $data,
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to fetch fees: HTTP ' . $response->status(),
                'data'    => [],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
                'data'    => [],
            ];
        }
    }

    public function getFeeForWilaya(int $wilayaId, string $type = 'livraison', bool $isStopDesk = false): ?int
    {
        $result = $this->getAllFees();
        if (!$result['success']) {
            return null;
        }

        $fees = $result['data'][$type] ?? [];
        foreach ($fees as $fee) {
            if (($fee['wilaya_id'] ?? 0) == $wilayaId) {
                return (int) ($isStopDesk ? ($fee['tarif_stopdesk'] ?? 0) : ($fee['tarif'] ?? 0));
            }
        }

        return null;
    }

    public function createParcel(Order $order): array
    {
        if ($this->mockMode) {
            return $this->mockCreateParcel($order);
        }

        try {
            $order->load(['items', 'shop']);

            $payload = $this->buildParcelPayload($order);

            $response = Http::withToken($this->token)
                ->timeout(30)
                ->acceptJson()
                ->withoutVerifying()
                ->post($this->baseUrl . '/api/v1/create/order', $payload);

            $body = $response->json();

            if ($response->successful() && ($body['success'] ?? false)) {
                return [
                    'success'      => true,
                    'tracking'     => $body['data']['tracking'] ?? $body['data']['tracking_id'] ?? $body['tracking'] ?? null,
                    'message'      => $body['message'] ?? 'تم إنشاء الطرد بنجاح',
                    'raw_response' => $body,
                ];
            }

            if (($body['message'] ?? '') === 'Stop desk non disponible pour cette commune' || str_contains($body['message'] ?? '', 'Stop desk')) {
                $payload['stop_desk'] = 0;
                unset($payload['stop_desk']);

                $response2 = Http::withToken($this->token)
                    ->timeout(30)
                    ->acceptJson()
                    ->withoutVerifying()
                    ->post($this->baseUrl . '/api/v1/create/order', $payload);

                $body2 = $response2->json();

                if ($response2->successful() && ($body2['success'] ?? false)) {
                    return [
                        'success'      => true,
                        'tracking'     => $body2['data']['tracking'] ?? $body2['data']['tracking_id'] ?? $body2['tracking'] ?? null,
                        'message'      => 'تم إنشاء الطرد (توصيل للمنزل بدلاً من المكتب)',
                        'raw_response' => $body2,
                    ];
                }

                Log::error('DHD createParcel retry also failed', [
                    'order_number' => $order->order_number,
                    'status'       => $response2->status(),
                    'body'         => $body2,
                ]);

                return [
                    'success'      => false,
                    'tracking'     => null,
                    'message'      => $body2['message'] ?? $body2['error'] ?? 'فشل إنشاء الطرد',
                    'raw_response' => $body2,
                ];
            }

            Log::error('DHD createParcel failed', [
                'order_number' => $order->order_number,
                'status'       => $response->status(),
                'body'         => $body,
            ]);

            return [
                'success'      => false,
                'tracking'     => null,
                'message'      => $body['message'] ?? $body['error'] ?? 'Failed to create parcel',
                'raw_response' => $body,
            ];
        } catch (\Exception $e) {
            Log::error('DHD createParcel exception', [
                'order_number' => $order->order_number,
                'error'        => $e->getMessage(),
            ]);

            return [
                'success'      => false,
                'tracking'     => null,
                'message'      => 'Exception: ' . $e->getMessage(),
                'raw_response' => [],
            ];
        }
    }

    public function trackParcels(array $orderNumbers): array
    {
        if ($this->mockMode) {
            return $this->mockTrackParcels($orderNumbers);
        }

        $results = [];
        foreach ($orderNumbers as $orderNumber) {
            $results[] = [
                'order_number' => $orderNumber,
                'status'       => 'confirmed',
                'new_status'   => null,
                'message'      => "تتبع: https://suivi.ecotrack.dz/suivi/",
            ];
        }

        return [
            'success' => true,
            'results' => $results,
        ];
    }

    public function mapDhdStatusToSystem(string $dhdStatus): string
    {
        return self::STATUS_MAP[$dhdStatus] ?? 'pending';
    }

    protected function buildParcelPayload(Order $order): array
    {
        $shop = $order->shop;
        $items = $order->items;

        $productDescription = $items->map(fn($item) => $item->product_name . ' x' . $item->quantity)->implode(', ');

        $isStopDesk = str_contains($order->notes ?? '', 'المكتب') || str_contains($order->notes ?? '', 'المكتب');

        $payload = [
            'reference'    => $order->order_number,
            'nom_client'   => $order->customer_name,
            'telephone'    => preg_replace('/[^0-9]/', '', $order->customer_phone),
            'adresse'      => $order->customer_address ?? $order->commune,
            'commune'      => $this->validateCommune($order->commune, $this->getWilayaIdByName($order->wilaya) ?? 16),
            'code_wilaya'  => $this->getWilayaIdByName($order->wilaya) ?? 16,
            'montant'      => (float) $order->total,
            'produit'      => $productDescription,
            'type'         => 1,
            'boutique'     => $shop->name ?? '',
            'remarque'     => $order->notes ?? '',
        ];

        if ($isStopDesk) {
            $payload['stop_desk'] = 1;
        }

        return $payload;
    }

    protected function getWilayaIdByName(string $wilayaName): ?int
    {
        foreach (self::WILAYAS as $wilaya) {
            if ($wilaya['name'] === $wilayaName) {
                return $wilaya['id'];
            }
        }

        if (isset(self::WILAYA_NAME_TO_ID[$wilayaName])) {
            return self::WILAYA_NAME_TO_ID[$wilayaName];
        }

        return null;
    }

    protected function validateCommune(string $commune, int $wilayaId): string
    {
        if ($this->mockMode) {
            return $commune;
        }

        $result = $this->getCommunes($wilayaId);
        if (!$result['success'] || empty($result['data'])) {
            return $commune;
        }

        $validCommunes = $result['data'];

        if (in_array($commune, $validCommunes)) {
            return $commune;
        }

        foreach ($validCommunes as $valid) {
            if (strtolower($valid) === strtolower($commune)) {
                return $valid;
            }
        }

        foreach ($validCommunes as $valid) {
            if (levenshtein(strtolower($valid), strtolower($commune)) <= 3) {
                return $valid;
            }
        }

        return $commune;
    }

    protected function mockCommunes(int $wilayaId): array
    {
        $communes = [
            16 => ['الحراش', 'باب الزوار', 'بئر مراد رايس', 'بوزريعة', 'دالي إبراهيم', 'حسين داي', 'المدينة الجديدة علي منجلي', 'المرجان', 'وادي السمار'],
            31 => ['السانية', 'بئر الجير', 'بوفاريك', 'بوعرفة', 'قديل', 'العنصر', 'الهرادية', '生态环境'],
            9 => ['بوزرعة', 'البليدة', 'بوفاريك', 'بوعرفة', 'الشريعة', 'الصومعة', 'وادي العلايق'],
        ];

        return [
            'success' => true,
            'data'    => $communes[$wilayaId] ?? ['مركز البلدية'],
        ];
    }

    protected function mockFees(int $wilayaId): array
    {
        $basePrice = 400;
        foreach (self::WILAYAS as $w) {
            if ($w['id'] === $wilayaId) {
                $basePrice = $w['price'];
                break;
            }
        }

        return [
            'success' => true,
            'data'    => [
                'livraison'    => $basePrice,
                'retour'       => round($basePrice * 0.5),
                'echange'      => $basePrice + 200,
                'recouvrement' => round($basePrice * 0.03),
            ],
        ];
    }

    protected function mockCreateParcel(Order $order): array
    {
        $trackingCode = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

        return [
            'success'      => true,
            'tracking'     => 'DHD-' . $trackingCode,
            'message'      => 'تم إنشاء الطرد بنجاح',
            'raw_response' => [
                'success' => true,
                'data'    => [
                    'tracking'  => 'DHD-' . $trackingCode,
                    'reference' => $order->order_number,
                    'status'    => 'registered',
                    'message'   => 'تم إنشاء الطرد بنجاح',
                ],
            ],
        ];
    }

    protected function mockTrackParcels(array $orderNumbers): array
    {
        $results = [];

        foreach ($orderNumbers as $orderNumber) {
            $orderId = $this->extractOrderId($orderNumber);
            $status = $this->getMockStatus($orderId);

            $results[] = [
                'order_number' => $orderNumber,
                'status'       => $status,
                'new_status'   => $this->mapDhdStatusToSystem($status),
                'message'      => 'DHD-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6)),
            ];
        }

        return [
            'success' => true,
            'results' => $results,
        ];
    }

    protected function extractOrderId(string $orderNumber): int
    {
        if (preg_match('/(\d+)$/', $orderNumber, $matches)) {
            return (int) $matches[1];
        }

        return abs(crc32($orderNumber)) % 10000;
    }

    protected function getMockStatus(int $orderId): string
    {
        $mod = $orderId % 10;

        if ($mod < 3) {
            return 'Livré';
        } elseif ($mod <= 5) {
            return 'En cours de livraison';
        } elseif ($mod <= 7) {
            return 'Retour transféré';
        }

        return 'Retour au bureau';
    }
}
