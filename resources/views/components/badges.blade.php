@props(['type' => 'default'])

@php
    $classes = [
        'blood-group' => [
            'a+' => 'bg-red-100 text-red-800',
            'a-' => 'bg-red-200 text-red-900',
            'b+' => 'bg-blue-100 text-blue-800',
            'b-' => 'bg-blue-200 text-blue-900',
            'o+' => 'bg-green-100 text-green-800',
            'o-' => 'bg-green-200 text-green-900',
            'ab+' => 'bg-purple-100 text-purple-800',
            'ab-' => 'bg-purple-200 text-purple-900',
        ],
        'urgency' => [
            'critical' => 'bg-red-100 text-red-800',
            'high' => 'bg-orange-100 text-orange-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'low' => 'bg-gray-100 text-gray-800',
        ],
        'status' => [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'fulfilled' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ],
    ];

    $badgeType = $attributes->get('badge-type', 'blood-group');
    $value = $attributes->get('value');

    $class = $classes[$badgeType][strtolower($value)] ?? 'bg-gray-100 text-gray-800';
@endphp

<span
    {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$class}"]) }}>
    {{ ucfirst($value) }}
</span>
