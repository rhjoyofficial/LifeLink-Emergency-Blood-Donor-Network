<?php

namespace App\Services;

use App\Models\BloodRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BloodRequestService
{
  public function create(array $data, User $recipient): BloodRequest
  {
    return DB::transaction(function () use ($data, $recipient) {
      return BloodRequest::create([
        'recipient_id' => $recipient->id,
        'patient_name' => $data['patient_name'],
        'blood_group' => $data['blood_group'],
        'bags_required' => $data['bags_required'],
        'hospital_name' => $data['hospital_name'],
        'district' => $data['district'],
        'upazila' => $data['upazila'],
        'contact_phone' => $data['contact_phone'],
        'urgency_level' => $data['urgency_level'],
        'needed_at' => $data['needed_at'],
        'status' => 'pending',
      ]);
    });
  }

  public function approve(BloodRequest $bloodRequest, User $admin): BloodRequest
  {
    if ($bloodRequest->status !== 'pending') {
      throw new \Exception('Only pending requests can be approved.');
    }

    $bloodRequest->update([
      'status' => 'approved',
      'approved_by_admin' => $admin->id,
    ]);

    return $bloodRequest;
  }

  public function fulfill(BloodRequest $bloodRequest): BloodRequest
  {
    $bloodRequest->update([
      'status' => 'fulfilled',
    ]);

    return $bloodRequest;
  }

  public function cancel(BloodRequest $bloodRequest): BloodRequest
  {
    $bloodRequest->update([
      'status' => 'cancelled',
    ]);

    return $bloodRequest;
  }
}
