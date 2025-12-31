<?php

if (!function_exists('flash')) {
    /**
     * Flash a message to the session
     */
    function flash($message, $type = 'success', $duration = 5000, $description = null)
    {
        session()->flash('flash_message', [
            'message' => $message,
            'type' => $type,
            'duration' => $duration,
            'description' => $description
        ]);
    }
}