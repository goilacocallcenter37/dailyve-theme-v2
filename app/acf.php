<?php

namespace App;

/**
 * ACF Field Population Logic
 */

// Filters to populate select fields
add_filter('acf/load_field/name=search_from', __NAMESPACE__ . '\\populate_acf_select_route_field');
add_filter('acf/load_field/name=search_to', __NAMESPACE__ . '\\populate_acf_select_route_field');
add_filter('acf/load_field/name=route_departure_point', __NAMESPACE__ . '\\populate_acf_select_route_field');
add_filter('acf/load_field/name=route_destination_point', __NAMESPACE__ . '\\populate_acf_select_route_field');
add_filter('acf/load_field/name=coupon_route_departure', __NAMESPACE__ . '\\populate_acf_select_route_area_field');
add_filter('acf/load_field/name=coupon_route_destination', __NAMESPACE__ . '\\populate_acf_select_route_area_field');
add_filter('acf/load_field/name=schedule_departure_point', __NAMESPACE__ . '\\populate_acf_select_route_field');
add_filter('acf/load_field/name=schedule_destination_point', __NAMESPACE__ . '\\populate_acf_select_route_field');
add_filter('acf/load_field/name=company_id', __NAMESPACE__ . '\\populate_acf_select_company_field');
add_filter('acf/load_field/name=coupon_company', __NAMESPACE__ . '\\populate_acf_select_company_field');

/**
 * Populate Route Select Field (Province/City)
 */
function populate_acf_select_route_field($field)
{
    $cache_key = 'acf_route_choices_long_term';
    $cached_data = get_option($cache_key);
    $cache_time = get_option($cache_key . '_time', 0);

    // Cache valid for 24 hours
    if ($cache_time > 0 && (time() - $cache_time) <= DAY_IN_SECONDS && !empty($cached_data)) {
        $field['choices'] = $cached_data;
        return $field;
    }

    // Call REST API (internal)
    $response = handle_get_state_city_new();
    $data = $response->get_data();

    if ($data['success'] && !empty($data['data'])) {
        $choices = [];
        foreach ($data['data'] as $item) {
            if (isset($item['_id'], $item['name'])) {
                $choices[$item['_id']] = sanitize_text_field($item['nameWithType'] ?? $item['name']);
            }
        }

        if (!empty($choices)) {
            update_option($cache_key, $choices, false);
            update_option($cache_key . '_time', time(), false);
            $field['choices'] = $choices;
            return $field;
        }
    }

    // Fallback to old cache if API fails
    if (!empty($cached_data)) {
        $field['choices'] = $cached_data;
    }

    return $field;
}

/**
 * Populate Route Area (Level 1 only)
 */
function populate_acf_select_route_area_field($field)
{
    $response = handle_get_state_city_new();
    $data = $response->get_data();
    $field['choices'] = [];

    if ($data['success'] && !empty($data['data'])) {
        foreach ($data['data'] as $item) {
            if (isset($item['level']) && (int)$item['level'] === 1) {
                $field['choices'][$item['_id']] = $item['name'];
            }
        }
    }
    return $field;
}

/**
 * Populate Company Select Field
 */
function populate_acf_select_company_field($field)
{
    // In a real scenario, we might want to fetch this from an API as well.
    // For now, we use a subset of the dataCompany list or fetch from transient.
    $field['choices'] = get_transient('dailyve_company_choices');
    if ($field['choices']) {
        return $field;
    }

    // Fallback or static list if needed. 
    // Ideally, we should have a helper to fetch this from Dailyve API.
    $response = call_api_v2('/companies', 'GET', ['pageSize' => 1000]);
    if (!is_wp_error($response)) {
        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (!empty($data['data'])) {
            $choices = [];
            foreach ($data['data'] as $company) {
                $choices[$company['id']] = $company['name'];
            }
            set_transient('dailyve_company_choices', $choices, DAY_IN_SECONDS);
            $field['choices'] = $choices;
            return $field;
        }
    }

    return $field;
}
