<?php


namespace App\Service;


use App\Service\Interfaces\ExportServiceInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ExportService implements ExportServiceInterface
{
    public function exportToJson($transactions)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($transactions, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['createdAt', 'updatedAt', 'created_at', 'updated_at']]);

        $exportFileName = "exports/".date('YmdHis').".json";
        file_put_contents($exportFileName, $jsonContent);
    }
}