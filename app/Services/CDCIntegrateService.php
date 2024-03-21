<?php

namespace App\Services;
use App\Helper\Helper;
use App\Models\Status;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use App\Enums\CDCStatusEnum;
use GuzzleHttp\Psr7\Request;

class CDCIntegrateService
{
    public function checkOrderTrExistsInCDC($tr)
    {
        $client = new Client();
        $headers = [
            'EXTERNAL-API-KEY' => 'Ew%VVwaVq2E#4GE&'
            // 'EXTERNAL-API-KEY' => env('EXTERNAL_API_KEY')
        ];
        try{
            if (Str::contains($tr, 'BN') || Str::contains($tr, 'bn') || Str::contains($tr, 'Bn') || Str::contains($tr, 'bN')){
                $request = new Request('GET', env('CDC_API_URL'). '/api-external/v1/External/GetOrderByBN/' . $tr, $headers);
                $response = $client->sendAsync($request)->wait();
                return json_decode($response->getBody()->getContents(), true);
            }
            $request = new Request('GET', env('CDC_API_URL'). '/api-external/v1/External/GetOrderByRegistrationNo/' . $tr, $headers);
            $response = $client->sendAsync($request)->wait();
            return json_decode($response->getBody()->getContents(), true);
        }catch(\Exception $e){
            throw new \Exception('This tr not found');
        }
    }
    public function changeCDCOrderStatus($order, $status, $tr)
    {
        $client = new Client();
        $headers = [
            'EXTERNAL-API-KEY' => 'Ew%VVwaVq2E#4GE&'
            // 'EXTERNAL-API-KEY' => env('EXTERNAL_API_KEY')
        ];
        try{
            if(Str::contains($tr, 'BN') || Str::contains($tr, 'bn') || Str::contains($tr, 'Bn') || Str::contains($tr, 'bN')){
                $options['multipart'][0]['name'] = 'bn';
                $options['multipart'][0]['contents'] = $tr;
            }else{
                $options['multipart'][0]['name'] = 'registrationNo';
                $options['multipart'][0]['contents'] = $tr;
            }
            $options['multipart'][1]['name'] = 'status';
            $options['multipart'][1]['contents'] = $this->getOrderStatusEnum($status);
            $options['multipart'][2]['name'] = 'deliveryServiceProviderId';
            $options['multipart'][2]['contents'] = $order['delivery_service_provider_id'];
            $options['multipart'][3]['name'] = 'notes';
            $options['multipart'][3]['contents'] = $this->getOrderStatus($status, $order['notes']);
            $request = new Request('PUT', env('CDC_API_URL') . '/api-external/v1/External/UpdateOrderStatus', $headers);
            $response = $client->sendAsync($request, $options)->wait();
            return json_decode($response->getBody()->getContents(), true);
        }catch(\Exception $e){
            throw new \Exception('This tr not found');
        }
    }

    public function getOrderStatusEnum($status)
    {
        $statusInfo = Status::findOrFail($status);
        if($statusInfo->name == 'Received' || $statusInfo->name == 'Delayed')
            return CDCStatusEnum::in_delivery;
        if($statusInfo->name == 'Delivered')
            return CDCStatusEnum::delivered;
        if($statusInfo->name == 'Returned')
            return CDCStatusEnum::returned;
    }

    public function getOrderStatus($status, $notes = null)
    {
        $statusInfo = Status::findOrFail($status);
        if($statusInfo->name == 'Received')
            return $notes;
        if($statusInfo->name == 'Delayed')
            return $notes . '.\n موجل من قبل السائق';
        if($statusInfo->name == 'Delivered')
            return $notes;
        if($statusInfo->name == 'Returned')
            $text1 =  ' راجع من المندوب ' . '\n';
            $text2 =   ' ملاحظة المندوب:' . '\n';
            return $text1 . $text2 . ' ' . $notes;
    }

    public function checkOrderExistsInCDC($tr)
    {
        $client = new Client();
        $headers = [
            'EXTERNAL-API-KEY' => 'Ew%VVwaVq2E#4GE&'
            // 'EXTERNAL-API-KEY' => env('EXTERNAL_API_KEY')
        ];
        try{
            if (Str::contains($tr, 'BN') || Str::contains($tr, 'bn') || Str::contains($tr, 'Bn') || Str::contains($tr, 'bN')){
                $request = new Request('GET', env('CDC_API_URL'). '/api-external/v1/External/GetOrderByBN/' . $tr, $headers);
                $response = $client->sendAsync($request)->wait();
                return json_decode($response->getBody()->getContents(), true);
            }
            $request = new Request('GET', env('CDC_API_URL'). '/api-external/v1/External/GetOrderByRegistrationNo/' . $tr, $headers);
            $response = $client->sendAsync($request)->wait();
            return json_decode($response->getBody()->getContents(), true);
        }catch(\Exception $e){
            return false;
        }
    }
    public function getCompaniesFromCDC()
    {
        $client = new Client();
        $headers = [
            'EXTERNAL-API-KEY' => 'Ew%VVwaVq2E#4GE&'
        ];
        try{
            $request = new Request('GET', env('CDC_API_URL'). '/api-external/v1/External/GetDeliveryServiceProviders', $headers);
            $response = $client->sendAsync($request)->wait();
            return json_decode($response->getBody()->getContents(), true);
        }catch(\Exception $e){
            return false;
        }
    }
}

