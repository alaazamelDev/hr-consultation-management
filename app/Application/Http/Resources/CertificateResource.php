<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property mixed certificate_id
 * @property mixed file_url
 * @property mixed name
 */
class CertificateResource extends JsonResource
{

    public function toArray(Request $request)
    {
        return [
            'certificate_id' => $this->certificate_id,
            'certificate_name' => $this->name,
            'file' => $this->file_url ? Storage::url($this->file_url) : null,
        ];
    }
}
