<?php

return [
    // Type
    'boolean' => ['required', 'boolean'],
    'numeric' => ['required', 'numeric'],
    'integer' => ['required', 'integer'],
    'string' => ['required', 'string'],
    'array' => ['required', 'array'],
    'image' => ['required', 'image', 'mimes:jpg,jpeg,png,svg', 'max:10240'],
    'image_not_required' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:10240'],
    'date' => ['required', 'date'],
    // Common
    'id' => ['required', 'string', 'max:100'],
    'name' => ['required', 'string', 'max:100'],
    'title' => ['required', 'string', 'max:100'],
    'email' => ['required', 'string', 'email', 'max:100'],
    'password' => ['required', 'string', 'between:8,255'],
    'content' => ['required', 'string'],

    // User
    'phone_number' => ['string', 'between:10,12'],
    'address' => ['string', 'max:255'],

    // Contact
    'website' => ['required', 'url', 'max:100'],
    'attachment_path' => ['required_without', 'file'],
    'string_notrequired' => ['', 'max:255'],

    // Library
    'image_library' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:10240'],
];
