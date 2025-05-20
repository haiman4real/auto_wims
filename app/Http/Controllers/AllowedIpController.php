<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AllowedIp;
use Illuminate\Support\Facades\Log;
use App\Mail\OnboardIpNotificationMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdditionalInfoRequiredMail;

class AllowedIpController extends Controller
{
    public function index()
    {
        return response()->json(AllowedIp::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ip_address' => 'required|ip|unique:allowed_ips,ip_address',
            'environment' => 'required|string',
            'organization' => 'nullable|string',
            'status' => 'required|in:allowed,banned',
            'metadata' => 'nullable|array',
        ]);

        $allowedIp = AllowedIp::create($validated);
        return response()->json($allowedIp, 201);
    }

    public function update(Request $request, $id)
    {
        $allowedIp = AllowedIp::findOrFail($id);

        $validated = $request->validate([
            'ip_address' => 'nullable|ip|unique:allowed_ips,ip_address,' . $id,
            'environment' => 'nullable|string',
            'organization' => 'nullable|string',
            'status' => 'nullable|in:allowed,banned',
            'metadata' => 'nullable|array',
        ]);

        $allowedIp->update($validated);
        return response()->json($allowedIp);
    }

    public function destroy($id)
    {
        $allowedIp = AllowedIp::findOrFail($id);
        $allowedIp->delete();
        return response()->json(['message' => 'IP deleted successfully']);
    }

    public function onboardIp(Request $request)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'ip_address' => 'required|ip|unique:allowed_ips,ip_address',
                'environment' => 'required|string',
                'organization' => 'required|string',
                'description' => 'required',
                'onboarder_email' => 'required',
                'onboarder_name' => 'required',
            ]);

            // Capture additional system metadata
            $submitterIp = $request->ip(); // IP address of the submitter
            $systemMetadata = [
                'user_agent' => $request->header('User-Agent'), // User agent info
                'timestamp' => now()->toDateTimeString(), // Current timestamp
            ];

            // Construct the metadata field
            $metadata = [
                'note' => $validated['description'],
                'onboarder' => "IT Support URL",
                'onboarder_email' => $validated['onboarder_email'],
                'onboarder_name' => $validated['onboarder_name'],
                'submitter_ip' => $submitterIp,
                'system_metadata' => $systemMetadata,
            ];

            // Log the action
            Log::info('Attempting to onboard new IP', [
                'ip_address' => $validated['ip_address'],
                'environment' => $validated['environment'],
                'organization' => $validated['organization'],
                'submitter_ip' => $submitterIp,
            ]);

            // Create the new Allowed IP record
            $allowedIp = AllowedIp::create([
                'ip_address' => $validated['ip_address'],
                'environment' => $validated['environment'],
                'organization' => $validated['organization'],
                'metadata' => $metadata,
            ]);

            // Prepare action links
            $approveLink = route('ip.approve', $allowedIp->id);
            $declineLink = route('ip.decline', $allowedIp->id);
            $moreInfoLink = route('ip.requestMoreInfoForm', $allowedIp->id);

            // Send notification email to admins
            $adminEmails = ['haiman4real@gmail.com', 'davidogunwobi@gmail.com']; // Add admin emails here
            foreach ($adminEmails as $email) {
                Mail::to($email)->send(
                    new OnboardIpNotificationMail(
                        $allowedIp->ip_address,
                        $allowedIp->organization,
                        $metadata['note'], // Pass the note
                        $approveLink,
                        $declineLink,
                        $moreInfoLink,
                        $metadata['onboarder_name'] // Pass the onboarder name
                    )
                );
            }

            // Log success
            Log::info('Successfully onboarded IP and notification sent', ['id' => $allowedIp->id, 'ip_address' => $allowedIp->ip_address]);

            // Return a success message
            return response()->json([
                'message' => "The IP address {$allowedIp->ip_address} was successfully onboarded.",
            ], 201);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to onboard IP', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return a JSON error response
            return response()->json([
                'message' => 'An error occurred while onboarding the IP address.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function approveIp($id)
    {
        try {
            $allowedIp = AllowedIp::findOrFail($id);

            // Prevent the action if it has already been performed
            if ($allowedIp->status === 'allowed') {
                return response()->json(['message' => "The IP address {$allowedIp->ip_address} has already been approved."], 400);
            }

            // Prevent the action if it has already been performed
            if ($allowedIp->status === 'banned') {
                return response()->json(['message' => "The IP address {$allowedIp->ip_address} has already been rejected Admin Override Required!"], 400);
            }

            $allowedIp->status = 'allowed';
            $allowedIp->save();

            Log::info("IP address {$allowedIp->ip_address} approved.");
            return response()->json(['message' => "The IP address {$allowedIp->ip_address} has been approved."]);
        } catch (\Exception $e) {
            Log::error('Error approving IP address', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error approving IP address.', 'error' => $e->getMessage()], 500);
        }
    }

    public function declineIp($id)
    {
        try {
            $allowedIp = AllowedIp::findOrFail($id);
            // Prevent the action if it has already been performed
            if ($allowedIp->status === 'allowed') {
                return response()->json(['message' => "The IP address {$allowedIp->ip_address} has already been approved. Admin Override Required!"], 400);
            }

            // Prevent the action if it has already been performed
            if ($allowedIp->status === 'banned') {
                return response()->json(['message' => "The IP address {$allowedIp->ip_address} has already been rejected."], 400);
            }

            $allowedIp->status = 'banned';
            $allowedIp->save();

            Log::info("IP address {$allowedIp->ip_address} declined.");
            return response()->json(['message' => "The IP address {$allowedIp->ip_address} has been declined."]);
        } catch (\Exception $e) {
            Log::error('Error declining IP address', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error declining IP address.', 'error' => $e->getMessage()], 500);
        }
    }

    public function requestMoreInfo($id)
    {
        try {
            $allowedIp = AllowedIp::findOrFail($id);
            //add mail to send to Onboarder
            Log::info("Requested more information for IP address {$allowedIp->ip_address}.");
            return response()->json(['message' => "A request for more information on IP address {$allowedIp->ip_address} has been sent."]);
        } catch (\Exception $e) {
            Log::error('Error requesting more information for IP address', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error requesting more information.', 'error' => $e->getMessage()], 500);
        }
    }

    public function loadRequestInfoForm($id)
    {
        $allowedIp = AllowedIp::findOrFail($id);

        return view('request_more_info_form', compact('allowedIp'));
    }

    public function submitRequestMoreInfo(Request $request, $id)
    {
        try {
            $request->validate([
                'details' => 'required|string',
            ]);

            $allowedIp = AllowedIp::findOrFail($id);

            if ($request->hasFile('document-upload')) {
                $file = $request->file('document-upload');
                $filePath = $file->store('uploads', 'public');
                $metadata['uploaded_file'] = $filePath;
            }

            // Update the metadata with the requested details
            $metadata = $allowedIp->metadata;
            $metadata['additional_details_requested'] = $request->details;
            $allowedIp->metadata = $metadata;
            $allowedIp->save();

            // Send an email to the onboarder
            $onboarderEmail = $metadata['onboarder_email'] ?? 'default@example.com'; // Ensure onboarder email is available
            Mail::to($onboarderEmail)->send(new AdditionalInfoRequiredMail($allowedIp, $request->details));

            return view('info-done')->with(['status' => true, 'message' => 'Additional details have been requested']);

            // return response()->json(['message' => 'Additional details have been requested and documented.'], 200);
        } catch (\Exception $e) {
            Log::error('Failed to request additional information', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with(['error' => true, 'message' => 'An error occurred while requesting additional information. Please try again.'.$e->getMessage()])->withInput();

            // return response()->json([
            //     'message' => 'An error occurred while requesting additional information.',
            //     'error' => $e->getMessage(),
            // ], 500);
        }
    }

    public function userViewRequestedInfo($id)
    {
        $allowedIp = AllowedIp::findOrFail($id);

        return view('submit_more_info', compact('allowedIp'));
    }

    public function userSubmitRequestedInfo(Request $request, $id)
    {
        try {
            $request->validate([
                'details' => 'required|string',
            ]);

            $allowedIp = AllowedIp::findOrFail($id);

            if ($request->hasFile('document-upload')) {
                $file = $request->file('document-upload');
                $filePath = $file->store('uploads', 'public');
                $metadata['uploaded_file_submission'] = $filePath;
            }

            // Update the metadata with the requested details
            $metadata = $allowedIp->metadata;
            $metadata['additional_details_requested_submission'] = $request->details;
            $allowedIp->metadata = $metadata;
            $allowedIp->save();

           // Prepare action links
           $approveLink = route('ip.approve', $allowedIp->id);
           $declineLink = route('ip.decline', $allowedIp->id);
           $moreInfoLink = route('ip.requestMoreInfoForm', $allowedIp->id);

           //extract metadata to string
              $metadata = $allowedIp->metadata;
              $metadataString = json_encode($metadata);
              $metadataString = str_replace('"', '', $metadataString);
                $metadataString = str_replace('{', '', $metadataString);
                $metadataString = str_replace('}', '', $metadataString);
                $metadataString = str_replace(',', ', ', $metadataString);
                $metadataString = str_replace(':', ': ', $metadataString);
                $metadataString = str_replace('onboarder_name', 'Onboarder Name', $metadataString);
                $metadataString = str_replace('onboarder_email', 'Onboarder Email', $metadataString);
                $metadataString = str_replace('submitter_ip', 'Submitter IP', $metadataString);
                $metadataString = str_replace('system_metadata', 'System Metadata', $metadataString);
                $metadataString = str_replace('note', 'Note', $metadataString);
                $metadataString = str_replace('onboarder', 'Onboarder', $metadataString);
                $metadataString = str_replace('uploaded_file', 'Uploaded File', $metadataString);
                $metadataString = str_replace('additional_details_requested', 'Additional Details Requested', $metadataString);
                $metadataString = str_replace('uploaded_file_submission', 'Uploaded File Submission', $metadataString);
                $metadataString = str_replace('additional_details_requested_submission', 'Additional Details Requested Submission', $metadataString);
                $metadataString = str_replace('user_agent', 'User Agent', $metadataString);
                $metadataString = str_replace('timestamp', 'Timestamp', $metadataString);
                $metadataString = str_replace(' ', '<br>', $metadataString);
                $metadataString = str_replace(',', '<br>', $metadataString);

           // Send notification email to admins
           $adminEmails = ['haiman4real@gmail.com', 'davidogunwobi@gmail.com']; // Add admin emails here
           foreach ($adminEmails as $email) {
               Mail::to($email)->send(
                   new OnboardIpNotificationMail(
                       $allowedIp->ip_address,
                       $allowedIp->organization,
                       $metadataString, // Pass the note
                       $approveLink,
                       $declineLink,
                       $moreInfoLink,
                       $metadata['onboarder_name'] // Pass the onboarder name
                   )
               );
           }
            // $onboarderEmail = $metadata['onboarder_email'] ?? 'default@example.com'; // Ensure onboarder email is available
            // Mail::to($onboarderEmail)->send(new AdditionalInfoRequiredMail($allowedIp, $request->details));
            return view('info-done')->with(['status' => true, 'message' => 'Additional details have been submitted and documented']);
            // return response()->json(['message' => 'Additional details have been requested and documented.'], 200);
        } catch (\Exception $e) {
            Log::error('Failed to request additional information', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with(['error' => true, 'message' => 'An error occurred while requesting additional information. Please try again.'.$e->getMessage()])->withInput();

            // return response()->json([
            //     'message' => 'An error occurred while requesting additional information.',
            //     'error' => $e->getMessage(),
            // ], 500);
        }
    }
}
