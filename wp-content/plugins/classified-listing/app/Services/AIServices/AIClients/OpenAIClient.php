<?php

namespace Rtcl\Services\AIServices\AIClients;

use Rtcl\Helpers\Functions;

/**
 * Class OpenAIClient
 *
 * This class integrates OpenAI's API for generating AI-driven responses.
 */
class OpenAIClient {
	/**
	 * @var string Model to be used for AI responses.
	 */
	protected $model = '';

	protected $token = '200';

	/**
	 * OpenAIClient constructor.
	 *
	 * Initializes the OpenAI client and sets API key and model from settings.
	 *
	 * @throws \Exception If required settings are missing.
	 */
	public function __construct() {
		$apiKey 		= Functions::get_option_item('rtcl_ai_settings' ,'gpt_api_key');
		$this->model 	= Functions::get_option_item('rtcl_ai_settings' ,'gpt_models');
		$this->token 	= Functions::get_option_item('rtcl_ai_settings' ,'gpt_max_token');
		if (empty($apiKey) || empty($this->model)) {
			throw new \Exception('AI settings are not properly configured.');
		}
	}

	/**
	 * Generates a response from the AI model based on the given prompt.
	 *
	 * @param string $prompt The input text for the AI to respond to.
	 *
	 * @return string The AI-generated response or an error message.
	 */
	public function ask(string $prompt, string $system_prompt): string {
		$apiKey = Functions::get_option_item('rtcl_ai_settings', 'gpt_api_key');
		$url = 'https://api.openai.com/v1/chat/completions'; // OpenAI API endpoint

		$response = wp_remote_post($url, [
			'headers' => [
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $apiKey,
			],
			'body' => json_encode([
				'model' => $this->model, // Specify the model (e.g., "gpt-4")
				'messages' => [
					['role' => 'system', 'content' => $system_prompt.'give me plain text no markdown and no html'],
					['role' => 'user', 'content' => $prompt],
				],
				'temperature' => 0.7,
				'max_tokens' => 500,
			]),
			'timeout' => 100000,  // Increase timeout to 20 seconds
		]);

		if (is_wp_error($response)) {
			return 'Error: ' . $response->get_error_message();
		}

		// Parse the response body
		$body = wp_remote_retrieve_body($response);
		$data = json_decode($body, true);

		if (isset($data['choices'][0]['message']['content'])) {
			$content = $data['choices'][0]['message']['content'];
			// Decode and format JSON response
			$jsonData = json_decode($content, true);
			if (json_last_error() === JSON_ERROR_NONE) {
				if (is_array($jsonData)) {
					return implode("\n", array_map(function($item) { return "- " . $item; }, $jsonData));
				} else {
					return json_encode($jsonData, JSON_PRETTY_PRINT);
				}
			} else {
				return $content;
			}
		}

		return 'No response from AI.';
	}

	public function askKeyword($data)
	{
		return	$this->callGPT4oForKeyword($data);
	}

	public function askFormField($data)
	{
		$theme = Functions::get_current_theme();
		$data = json_decode($data, true);
		$prompt = $data['prompt'] ?? '';
		return $this->callGPT4o($prompt,$theme);
	}

	/**
	 * Calls the GPT-4o API to generate a response based on the provided prompt and instruction.
	 *
	 * @param string $prompt The user’s prompt to send to the model.
	 * @param string $instruction The instruction to provide context for the model.
	 * @param float $temperature The temperature setting for randomness in the response. Default is 0.7.
	 * @param string $model The OpenAI model to use, default is 'gpt-4o-mini'.
	 * @return false|string The cleaned and formatted JSON response from the API.
	 */
	public function callOpenAI($prompt, $instruction, $temperature = 0.7, $model = null,$for='keyword')
	{
		$apiKey = Functions::get_option_item('rtcl_ai_settings', 'gpt_api_key');
		$url = 'https://api.openai.com/v1/chat/completions'; // OpenAI API endpoint

		$response = wp_remote_post($url, [
			'headers' => [
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $apiKey,
			],
			'body' => json_encode([
				'model' => $this->model,
				'messages' => [
					[
						'role' => 'system',
						'content' => $instruction,
					],
					[
						'role' => 'user',
						'content' => $prompt,
					],
				],
				'temperature' => $temperature,
			]),
			'timeout' => 100000,  // Increase timeout to 20 seconds
		]);

		if (is_wp_error($response)) {
			return 'Error: ' . $response->get_error_message();
		}

		// Parse the response body
		$body = wp_remote_retrieve_body($response);
		$data = json_decode($body, true);

		if (!empty($data['error'])) {
			return $data;
		}

		// Extract and clean response
		$aiResponse = $data['choices'][0]['message']['content'] ?? '[]';
		$cleanResponse = trim($aiResponse, "```json\n \t\r\0\x0B");

		if($for == 'keyword'){
			return wp_send_json_success(['data' => json_decode($cleanResponse,true)]);
		}

		// Return a JSON response with the cleaned and formatted content
		return wp_send_json_success(['response' => json_decode($cleanResponse,true)]);
	}

	/**
	 * Calls the GPT-4o API to generate form field suggestions based on the provided prompt.
	 *
	 * @param string $prompt The user’s prompt describing the form or fields to be suggested.
	 * @return string A JSON-encoded string containing the form field suggestions.
	 */
	public function callGPT4o($prompt, $theme)
	{
		$systemPrompts = [
			"cl-classified" => "You are a helpful assistant that suggests form fields for General Classified listings (Classima). Provide a JSON array of form field suggestions. Each object should have 'label', 'type', 'placeholder', 'section', and 'required' properties. The 'type' should be one of: address, business_hours, category, checkbox, color_picker, custom_html, date, description, email, excerpt, file, images, input_hidden, location, map, number, phone, pricing, radio, recaptcha, repeater, select, social_profiles, switch,  text, textarea, title, url, view_count, website, whatsapp, zipcode, terms_and_condition. Title type must be provided and For profession-based directories (e.g., Doctor, Lawyer), the label should be the profession name (e.g., Doctor Name, Lawyer Name) and the type should be 'title'.If description field the type should be 'description'. If the type is select, checkbox, or radio, options should be an array of values. Provide at least 20 fields across 3 sections.If the type is select, checkbox, or radio, options should be an array of values. Try to Terms and Conditions in the last .Provide at least 20 fields across 3 sections. Keep the answer short and only return the JSON format",

			"classima" => "You are a helpful assistant that suggests form fields for General Classified listings (Classima). Provide a JSON array of form field suggestions. Each object should have 'label', 'type', 'placeholder', 'section', and 'required' properties. The 'type' should be one of: address, business_hours, category, checkbox, color_picker, custom_html, date, description, email, excerpt, file, images, input_hidden, location, map, number, phone, pricing, radio, recaptcha, repeater, select, social_profiles, switch, text, textarea, title, url, view_count, website, whatsapp, zipcode, terms_and_condition. Title type must be provided and For profession-based directories (e.g., Doctor, Lawyer), the label should be the profession name (e.g., Doctor Name, Lawyer Name) and the type should be 'title'.If description field the type should be 'description'. If the type is select, checkbox, or radio, options should be an array of values. Provide at least 20 fields across 3 sections. If the type is select, checkbox, or radio, options should be an array of values.Try to Terms and Conditions in the last. Provide at least 20 fields across 3 sections. Keep the answer short and only return the JSON format",

			"homlisti" => "You are a helpful assistant that suggests form fields for Real Estate listings (Homlisti). Provide a JSON array of form field suggestions. Each object should have 'label', 'type', 'placeholder', 'section', and 'required' properties. The 'type' should be one of: address, business_hours, category, checkbox, color_picker, custom_html, date, description, email, excerpt, file, images, input_hidden, location, map, number, phone, pricing, radio, recaptcha, repeater, select, social_profiles, switch, text, textarea, title, url, view_count, website, whatsapp, zipcode, terms_and_condition. Include real estate-specific fields such as Property Features (checkbox), Parking (Yes/No), Bedrooms, Bathrooms, Property Size, Build Year, and Proposed Sale Type (Sell/Buy/Rent). If the type is select, checkbox, or radio, options should be an array of values.Try to Terms and Conditions in the last.If description field the type should be 'description'. Provide at least 20 fields across 3 sections. Keep the answer short and only return the JSON format.",

			"hotel_directory" => "You are a helpful assistant that suggests form fields for Hotel Directory listings. Provide a JSON array of form field suggestions. Each object should have 'label', 'type', 'placeholder', 'section', and 'required' properties. The 'type' should be one of: address, business_hours, category, checkbox, color_picker, custom_html, date, description, email, excerpt, file, images, input_hidden, location, map, number, phone, pricing, radio, recaptcha, repeater, select, social_profiles, switch, text, textarea, title, url, view_count, website, whatsapp, zipcode, terms_and_condition. Include hotel-specific fields such as Amenities (checkbox), Opening Hours, and Instant Booking. If the type is select, checkbox, or radio, options should be an array of values.Try to Terms and Conditions in the last.If description field the type should be 'description'. Provide at least 20 fields across 3 sections. Keep the answer short and only return the JSON format."
		];

		$defaultPrompt = "You are a helpful assistant that suggests form fields based on user prompts. Provide a JSON array of form field suggestions. Each object should have 'label', 'type', 'placeholder','section', and 'required' properties. The 'type' should be one of: address, business_hours, category, checkbox, color_picker, custom_html, date, description, email, excerpt, file, images, input_hidden, location, map, number, phone, pricing, radio, recaptcha, repeater, select, social_profiles, switch, text, textarea, title, url, view_count, website, whatsapp, zipcode, terms_and_condition. Title type must be provided and For profession-based directories (e.g., Doctor, Lawyer), the label should be the profession name (e.g., Doctor Name, Lawyer Name) and the type should be 'title'.If description field the type should be 'description'.If the type is select, checkbox, or radio, options should be an array of values. Provide at least 20 fields across 3 sections. Keep the answer short and only return the JSON format.";


		$systemPrompt = $systemPrompts[$theme] ?? $defaultPrompt;
		return $this->callOpenAI($prompt, $systemPrompt, 0.7, $this->model,'form');
	}

	/**
	 * Calls the GPT-4o API to generate relevant keywords based on the provided prompt.
	 *
	 * @param string $prompt The user’s prompt describing the context for keyword generation.
	 * @return string A JSON-encoded array of 10 relevant keywords.
	 */
	public function callGPT4oForKeyword($prompt)
	{
		$data = json_decode($prompt, true);
		$prompt = $data['prompt'] ?? '';
		return $this->callOpenAI(
			$prompt,
			"You are an AI that generates relevant keywords based on user prompts. Return only a JSON array of keywords without any extra text. Provide exactly 10 keywords."
		);
	}
}
