<?php

namespace RtclPro\Models;

use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;

class FBFilterCFValidation {

	private $params;
	private $directoryData;
	private FBField $field;
	private array $fields;

	public function __construct( $params, $directoryData ) {
		$this->directoryData = $directoryData;
		$this->setParams( $params );
		$fields = [];
		if ( !empty( $this->directoryData[FBField::PRESET] ) ) {
			$fields = $this->directoryData[FBField::PRESET];
		}
		if ( !empty( $this->directoryData[FBField::CUSTOM] ) ) {
			$fields = $fields + $this->directoryData[FBField::CUSTOM];
		}
		$this->fields = $fields;
	}

	/**
	 * @param array|null $params
	 * @return void
	 */
	private function setParams( $params ) {
		if ( is_array( $params ) ) {
			$_params = [];

			foreach ( $params as $key => $value ) {
				if ( $key ) {
					$cleanKey = str_replace( [ 'filter_', 'cf_' ], '', $key );
					if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {
						if ( !empty( $params['filters'][$cleanKey] ) ) {
							$_params[$cleanKey] = $params['filters'][$cleanKey];
						} else {
							if ( ( is_array( $value ) && !empty( $value ) ) || ( is_string( $value ) && $value !== '' ) ) {
								if ( is_string( $value ) ) {
									$_params[$cleanKey] = explode( ',', $value );
								} else {
									$_params[$cleanKey] = $value;
								}
							}
						}
					} else {
						$_params[$cleanKey] = $value;
					}
				}
			}

			$params = $_params;
		} else {
			$params = [];
		}

		$this->params = $params;
	}

	/**
	 * @return mixed
	 */
	public function getParams() {
		return $this->params;
	}

	public function getField() {
		return $this->field;
	}

	/**
	 * @param FBField $field
	 * @return boolean
	 */
	public function fieldIsValidCondition( FBField $field ): bool {
		$this->field = $field;

		return $this->isValidateCondition();
	}

	/**
	 * @return boolean
	 */
	private function isValidateCondition(): bool {
		$data = $this->directoryData;

		// check is validated for section condition
		$sections = !empty( $data[FBField::SECTIONS] ) ? $data[FBField::SECTIONS] : [];
		if ( !empty( $sections ) ) {
			foreach ( $sections as $sectionIndex => $section ) {

				if ( empty( $section['logics']['status'] ) || empty( $section['logics']['conditions'] ) ) {
					continue;
				}

				// Casing loop
				if ( isset( $data[FBField::SECTIONS][$sectionIndex]['fieldsIds'] ) ) {
					$fieldsIds = $data[FBField::SECTIONS][$sectionIndex]['fieldsIds'];
				} else {
					$fieldsIds = [];
					if ( !empty( $section['columns'] ) ) {
						foreach ( $section['columns'] as $column ) {
							if ( !empty( $column['fields'] ) && is_array( $column['fields'] ) ) {
								$fieldsIds = array_merge( $fieldsIds, $column['fields'] );
							}
						}
					}
					$data[FBField::SECTIONS][$sectionIndex]['fieldsIds'] = $fieldsIds;
				}

				if ( !in_array( $this->field->getUuid(), $fieldsIds ) ) {
					continue;
				}
				$logics = $section['logics'];
				if ( !FBHelper::isValidateCondition( $this->params, $logics, $this->fields ) ) {
					return false;
				}

			}
		}

		// Field validation
		$fieldLogics = $this->field->getLogics();
		if ( !empty( $fieldLogics['status'] ) && !empty( $fieldLogics['conditions'] ) ) {
			return FBHelper::isValidateCondition( $this->params, $fieldLogics, $this->fields );
		}


		return true;
	}


}