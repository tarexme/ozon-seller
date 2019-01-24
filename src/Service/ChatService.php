<?php

namespace Gam6itko\OzonSeller\Service;

class ChatService extends AbstractService
{
    /**
     * Retrieves a list of chats in which a seller participates
     * @param array $query
     * @return mixed
     * @throws \Exception
     */
    public function list(array $query = [])
    {
        $whitelist = ['chat_id_list', 'page', 'page_size'];
        $query = array_intersect_key($query, array_flip($whitelist));

        return $this->request('POST', "/v1/chat/list", ['body' => \GuzzleHttp\json_encode($query)])['result'];
    }

    /**
     * Retreives message history in a chat.
     * @param string $chatId
     * @param array $query
     * @return mixed
     * @throws \Exception
     */
    public function history(string $chatId, array $query = [])
    {
        $whitelist = ['from_message_id', 'limit'];
        $query = array_intersect_key($query, array_flip($whitelist));

        $query['chat_id'] = $chatId;

        return $this->request('POST', "/v1/chat/history", ['body' => \GuzzleHttp\json_encode($query)])['result'];
    }

    /**
     * Sends a message in an existing chat with a customer
     * @param string $chatId
     * @param string $text
     * @return mixed
     * @throws \Exception
     */
    public function sendMessage(string $chatId, string $text): bool
    {
        $arr = [
            'chat_id' => $chatId,
            'text'    => $text
        ];

        $response = $this->request('POST', "/v1/chat/send/message", ['body' => \GuzzleHttp\json_encode($arr)]);
        return 'success' === $response['result'];
    }

    /**
     * Sends a file in an existing chat with a customer
     * @param string $base64Content File encoded in base64 string
     * @param string $chatId Unique chat ID
     * @param string $name File name with extension
     * @return array|string
     */
    public function sendFile(string $base64Content, string $chatId, string $name)
    {
        $arr = [
            'base64_content' => $base64Content,
            'chat_id'        => $chatId,
            'name'           => $name
        ];
        $response = $this->request('POST', "/v1/chat/send/file", ['body' => \GuzzleHttp\json_encode($arr)]);
        return 'success' === $response['result'];
    }

    /**
     * Creates a new chat with a customer related to a specific order.
     * For example, if a seller has some questions regarding delivery address, he can simply contact a buyer via new chat
     * @param int $orderId
     * @return bool
     * @throws \Exception
     */
    public function start(int $orderId)
    {
        $arr = [
            'order_id' => $orderId
        ];
        return $this->request('POST', "/v1/chat/start", ['body' => \GuzzleHttp\json_encode($arr)])['result'];
    }
}