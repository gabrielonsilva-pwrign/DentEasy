<?php

namespace App\Controllers;

use App\Models\WebhookModel;
use CodeIgniter\API\ResponseTrait;

class Api extends BaseController
{
    use ResponseTrait;

    protected $webhookModel;

    public function __construct()
    {
        $this->webhookModel = new WebhookModel();
    }

    public function index()
    {
        $this->checkPermission('api','view');
        $data['webhooks'] = $this->webhookModel->findAll();
        return view('api/index', $data);
    }

    public function new()
    {
        $this->checkPermission('api','add');
        return view('api/create');
    }

    public function create()
    {
        $this->checkPermission('api','add');
        $data = $this->request->getPost();

        if ($this->webhookModel->insert($data)) {
            return redirect()->to('/api')->with('success', 'Webhook adicionado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->webhookModel->errors());
        }
    }

    public function edit($id = null)
    {
        $this->checkPermission('api','edit');
        $data['webhook'] = $this->webhookModel->find($id);
        return view('api/edit', $data);
    }

    public function update($id = null)
    {
        $this->checkPermission('api','edit');
        $data = $this->request->getPost();

        if ($this->webhookModel->update($id, $data)) {
            return redirect()->to('/api')->with('success', 'Webhook atualizado');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->webhookModel->errors());
        }
    }

    public function delete($id = null)
    {
        $this->checkPermission('api','delete');
        if ($this->webhookModel->delete($id)) {
            return redirect()->to('/api')->with('success', 'Webhook excluído');
        } else {
            return redirect()->back()->with('error', 'Falha ao excluir webhook');
        }
    }

    public function sendWebhook($data)
    {
        $activeWebhook = $this->webhookModel->getActiveWebhook();

        if ($activeWebhook) {
            $client = \Config\Services::curlrequest();

            try {
                $response = $client->post($activeWebhook['url'], [
                    'json' => $data
                ]);

                log_message('info', 'Webhook enviado: ' . json_encode($data));
                return true;
            } catch (\Exception $e) {
                log_message('error', 'Falha ao enviar webhook: ' . $e->getMessage());
                return false;
            }
        }

        return false;
    }
}
