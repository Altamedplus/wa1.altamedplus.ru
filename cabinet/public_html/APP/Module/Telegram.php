<?php

namespace APP\Module;

use Exception;

class Telegram
{
    private $token;
    private $apiUrl;
    private $webhookUrl;

    /**
     * Constructor
     * @param string $token Bot token from BotFather
     */
    public function __construct($token = false)
    {
        $this->token = $token ?: self::getTokenEnv();
        $this->apiUrl = "https://api.telegram.org/bot{$this->token}/";
    }
    public static function getTokenEnv()
    {
        if (!defined('TOKEN_TELEGRAMM')) {
            throw new Exception('Error not found TOKEN_TELEGRAMM');
        }
        return TOKEN_TELEGRAMM;
    }
    /**
     * Send API request to Telegram
     * @param string $method API method
     * @param array $data Request data
     * @return array Response
     */
    private function request($method, $data = [])
    {
        $url = $this->apiUrl . $method;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return ['ok' => false, 'description' => $error];
        }
        
        return json_decode($response, true);
    }
    
    /**
     * Set webhook for bot
     * @param string $url Webhook URL
     * @param array $options Additional options (certificate, max_connections, etc.)
     * @return array Response
     */
    public function setWebhook($url, $options = [])
    {
        $this->webhookUrl = $url;
        $data = ['url' => $url];
        
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        return $this->request('setWebhook', $data);
    }
    
    /**
     * Delete webhook
     * @param bool $dropPendingUpdates Drop all pending updates
     * @return array Response
     */
    public function deleteWebhook($dropPendingUpdates = false)
    {
        return $this->request('deleteWebhook', ['drop_pending_updates' => $dropPendingUpdates]);
    }
    
    /**
     * Get webhook info
     * @return array Webhook information
     */
    public function getWebhookInfo()
    {
        return $this->request('getWebhookInfo');
    }
    
    /**
     * Handle incoming webhook update
     * @return object|null Update object or null if invalid
     */
    public function handleWebhook()
    {
        $content = file_get_contents('php://input');
        
        if (empty($content)) {
            return null;
        }
        
        $update = json_decode($content);
        
        if (!$update) {
            return null;
        }
        
        return $update;
    }
    
    /**
     * Send message
     * @param int|string $chatId Chat ID
     * @param string $text Message text
     * @param array $options Additional options (parse_mode, disable_web_page_preview, etc.)
     * @return array Response
     */
    public function sendMessage($chatId, $text, $options = [])
    {
        $data = [
            'chat_id' => $chatId,
            'text' => $text
        ];
        
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        return $this->request('sendMessage', $data);
    }
    
    /**
     * Send photo
     * @param int|string $chatId Chat ID
     * @param string $photo Photo URL or file_id
     * @param string $caption Caption (optional)
     * @param array $options Additional options
     * @return array Response
     */
    public function sendPhoto($chatId, $photo, $caption = '', $options = [])
    {
        $data = [
            'chat_id' => $chatId,
            'photo' => $photo,
            'caption' => $caption
        ];
        
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        return $this->request('sendPhoto', $data);
    }
    
    /**
     * Send document
     * @param int|string $chatId Chat ID
     * @param string $document Document URL or file_id
     * @param string $caption Caption (optional)
     * @param array $options Additional options
     * @return array Response
     */
    public function sendDocument($chatId, $document, $caption = '', $options = [])
    {
        $data = [
            'chat_id' => $chatId,
            'document' => $document,
            'caption' => $caption
        ];
        
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        return $this->request('sendDocument', $data);
    }
    
    /**
     * Send audio
     * @param int|string $chatId Chat ID
     * @param string $audio Audio URL or file_id
     * @param string $caption Caption (optional)
     * @param array $options Additional options
     * @return array Response
     */
    public function sendAudio($chatId, $audio, $caption = '', $options = [])
    {
        $data = [
            'chat_id' => $chatId,
            'audio' => $audio,
            'caption' => $caption
        ];
        
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        return $this->request('sendAudio', $data);
    }
    
    /**
     * Send video
     * @param int|string $chatId Chat ID
     * @param string $video Video URL or file_id
     * @param string $caption Caption (optional)
     * @param array $options Additional options
     * @return array Response
     */
    public function sendVideo($chatId, $video, $caption = '', $options = [])
    {
        $data = [
            'chat_id' => $chatId,
            'video' => $video,
            'caption' => $caption
        ];
        
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        return $this->request('sendVideo', $data);
    }
    
    /**
     * Send voice
     * @param int|string $chatId Chat ID
     * @param string $voice Voice URL or file_id
     * @param string $caption Caption (optional)
     * @param array $options Additional options
     * @return array Response
     */
    public function sendVoice($chatId, $voice, $caption = '', $options = [])
    {
        $data = [
            'chat_id' => $chatId,
            'voice' => $voice,
            'caption' => $caption
        ];
        
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        return $this->request('sendVoice', $data);
    }
    
    /**
     * Send location
     * @param int|string $chatId Chat ID
     * @param float $latitude Latitude
     * @param float $longitude Longitude
     * @param array $options Additional options
     * @return array Response
     */
    public function sendLocation($chatId, $latitude, $longitude, $options = [])
    {
        $data = [
            'chat_id' => $chatId,
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
        
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        return $this->request('sendLocation', $data);
    }
    
    /**
     * Send chat action (typing, upload_photo, etc.)
     * @param int|string $chatId Chat ID
     * @param string $action Action type
     * @return array Response
     */
    public function sendChatAction($chatId, $action)
    {
        $data = [
            'chat_id' => $chatId,
            'action' => $action
        ];
        
        return $this->request('sendChatAction', $data);
    }
    
    /**
     * Get file info
     * @param string $fileId File ID
     * @return array File info
     */
    public function getFile($fileId)
    {
        return $this->request('getFile', ['file_id' => $fileId]);
    }
    
    /**
     * Get file URL
     * @param string $filePath File path from getFile response
     * @return string File URL
     */
    public function getFileUrl($filePath)
    {
        return "https://api.telegram.org/file/bot{$this->token}/{$filePath}";
    }
    
    /**
     * Get bot information
     * @return array Bot information
     */
    public function getMe()
    {
        return $this->request('getMe');
    }
    
    /**
     * Edit message text
     * @param array $options Options (chat_id, message_id, text, etc.)
     * @return array Response
     */
    public function editMessageText($options)
    {
        return $this->request('editMessageText', $options);
    }
    
    /**
     * Delete message
     * @param int|string $chatId Chat ID
     * @param int $messageId Message ID
     * @return array Response
     */
    public function deleteMessage($chatId, $messageId)
    {
        return $this->request('deleteMessage', [
            'chat_id' => $chatId,
            'message_id' => $messageId
        ]);
    }
    
    /**
     * Answer callback query
     * @param string $callbackQueryId Callback query ID
     * @param array $options Additional options
     * @return array Response
     */
    public function answerCallbackQuery($callbackQueryId, $options = [])
    {
        $data = ['callback_query_id' => $callbackQueryId];
        
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }
        
        return $this->request('answerCallbackQuery', $data);
    }
}
