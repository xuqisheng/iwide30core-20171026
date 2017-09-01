<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function data_dehydrate($data, $fields = array(), $key = '') {
	$no_water = array ();
	if (! empty ( $key )) {
		foreach ( $data as $d ) {
			foreach ( $fields as $f ) {
				$no_water [$d [$key]] [$f] = $d [$f];
			}
		}
	} else {
		foreach ( $data as $k => $d ) {
			foreach ( $fields as $f ) {
				$no_water [$k] [$f] = $d [$f];
			}
		}
	}
	return $no_water;
}
