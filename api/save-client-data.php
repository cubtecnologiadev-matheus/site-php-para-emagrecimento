<?php
date_default_timezone_set('America/Sao_Paulo');

function saveClientData($orderData) {
    try {
        $clientsDir = dirname(__DIR__) . '/clientes';
        if (!file_exists($clientsDir)) {
            mkdir($clientsDir, 0755, true);
        }
        
        // Encontrar próximo número de cliente
        $clientNumber = 1;
        while (file_exists($clientsDir . '/cliente' . $clientNumber)) {
            $clientNumber++;
        }
        
        // Criar pasta do cliente
        $clientDir = $clientsDir . '/cliente' . $clientNumber;
        mkdir($clientDir, 0755, true);
        
        // Extrair dados do orderData
        $simulation = $orderData['simulation'] ?? [];
        $customer = $orderData['customer'] ?? [];
        $documents = $orderData['documents'] ?? [];
        
        // Salvar dados do cliente em JSON
        $clientData = [
            'numero_cliente' => $clientNumber,
            'data_pedido' => date('Y-m-d H:i:s'),
            'cliente' => [
                'nome' => $customer['nome'] ?? '',
                'cpf' => $customer['cpf'] ?? '',
                'email' => $customer['email'] ?? '',
                'telefone' => $customer['telefone'] ?? '',
                'data_nascimento' => $customer['data_nascimento'] ?? ''
            ],
            'endereco_entrega' => [
                'cep' => $customer['cep'] ?? '',
                'logradouro' => $customer['logradouro'] ?? '',
                'numero' => $customer['numero'] ?? '',
                'complemento' => $customer['complemento'] ?? '',
                'bairro' => $customer['bairro'] ?? '',
                'cidade' => $customer['cidade'] ?? '',
                'uf' => $customer['uf'] ?? ''
            ],
            'produto' => [
                'nome' => $simulation['produto_nome'] ?? '',
                'preco' => floatval($simulation['produto_preco'] ?? 0),
                'imagem' => $simulation['produto_imagem'] ?? ''
            ],
            'financiamento' => [
                'entrada' => floatval($simulation['entrada_personalizada'] ?? ($simulation['entrada'] ?? 0)),
                'parcelas' => intval($simulation['parcelas_personalizadas'] ?? ($simulation['parcelas'] ?? 1)),
                'valor_parcela' => 0,
                'melhor_dia' => $simulation['melhor_dia'] ?? '10',
                'valor_total' => 0
            ],
            'documentos' => [
                'tipo' => $documents['type'] ?? '',
                'rg_frente' => '',
                'rg_verso' => '',
                'status' => 'documentos_pendentes'
            ],
            'status_pedido' => 'aguardando_pagamento',
            'dados_completos' => $orderData
        ];
        
        // Calcular valores do financiamento
        $valor = $clientData['produto']['preco'];
        $entrada = $clientData['financiamento']['entrada'];
        $parcelas = $clientData['financiamento']['parcelas'];
        
        if ($parcelas > 0) {
            $valorParcela = ($valor - $entrada) / $parcelas;
            $clientData['financiamento']['valor_parcela'] = $valorParcela;
            $clientData['financiamento']['valor_total'] = $entrada + ($valorParcela * $parcelas);
        }
        
        // Salvar JSON
        $jsonFile = $clientDir . '/dados_cliente.json';
        file_put_contents($jsonFile, json_encode($clientData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        // Copiar documentos se existirem
        if (!empty($documents)) {
            if ($documents['type'] === 'rg') {
                if (isset($documents['rg_frente']) && !empty($documents['rg_frente'])) {
                    $sourceFile = $documents['rg_frente'];
                    if (!file_exists($sourceFile) && !str_starts_with($sourceFile, '/')) {
                        $sourceFile = dirname(__DIR__) . '/' . $sourceFile;
                    }
                    
                    if (file_exists($sourceFile)) {
                        $extension = pathinfo($sourceFile, PATHINFO_EXTENSION);
                        $destFile = $clientDir . '/rg_frente.' . $extension;
                        copy($sourceFile, $destFile);
                        $clientData['documentos']['rg_frente'] = 'rg_frente.' . $extension;
                    }
                }
                
                if (isset($documents['rg_verso']) && !empty($documents['rg_verso'])) {
                    $sourceFile = $documents['rg_verso'];
                    if (!file_exists($sourceFile) && !str_starts_with($sourceFile, '/')) {
                        $sourceFile = dirname(__DIR__) . '/' . $sourceFile;
                    }
                    
                    if (file_exists($sourceFile)) {
                        $extension = pathinfo($sourceFile, PATHINFO_EXTENSION);
                        $destFile = $clientDir . '/rg_verso.' . $extension;
                        copy($sourceFile, $destFile);
                        $clientData['documentos']['rg_verso'] = 'rg_verso.' . $extension;
                    }
                }
            }
            
            if ($documents['type'] === 'cnh_fisica') {
                if (isset($documents['cnh_frente']) && !empty($documents['cnh_frente'])) {
                    $sourceFile = $documents['cnh_frente'];
                    if (!file_exists($sourceFile) && !str_starts_with($sourceFile, '/')) {
                        $sourceFile = dirname(__DIR__) . '/' . $sourceFile;
                    }
                    
                    if (file_exists($sourceFile)) {
                        $extension = pathinfo($sourceFile, PATHINFO_EXTENSION);
                        $destFile = $clientDir . '/cnh_frente.' . $extension;
                        copy($sourceFile, $destFile);
                        $clientData['documentos']['cnh_frente'] = 'cnh_frente.' . $extension;
                    }
                }
                
                if (isset($documents['cnh_verso']) && !empty($documents['cnh_verso'])) {
                    $sourceFile = $documents['cnh_verso'];
                    if (!file_exists($sourceFile) && !str_starts_with($sourceFile, '/')) {
                        $sourceFile = dirname(__DIR__) . '/' . $sourceFile;
                    }
                    
                    if (file_exists($sourceFile)) {
                        $extension = pathinfo($sourceFile, PATHINFO_EXTENSION);
                        $destFile = $clientDir . '/cnh_verso.' . $extension;
                        copy($sourceFile, $destFile);
                        $clientData['documentos']['cnh_verso'] = 'cnh_verso.' . $extension;
                    }
                }
            }
            
            if ($documents['type'] === 'cnh_digital' && isset($documents['cnh_pdf'])) {
                $sourceFile = $documents['cnh_pdf'];
                if (!file_exists($sourceFile) && !str_starts_with($sourceFile, '/')) {
                    $sourceFile = dirname(__DIR__) . '/' . $sourceFile;
                }
                
                if (file_exists($sourceFile)) {
                    $destFile = $clientDir . '/cnh_digital.pdf';
                    copy($sourceFile, $destFile);
                    $clientData['documentos']['cnh_digital'] = 'cnh_digital.pdf';
                }
            }
            
            if (!empty(array_filter($clientData['documentos'], function($v, $k) { 
                return $k !== 'tipo' && $k !== 'status' && !empty($v); 
            }, ARRAY_FILTER_USE_BOTH))) {
                $clientData['documentos']['status'] = 'documentos_recebidos';
            }
            
            file_put_contents($jsonFile, json_encode($clientData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
        
        // Criar arquivo de log
        $logFile = $clientDir . '/log_pedido.txt';
        $logContent = "=== LOG DO PEDIDO - CLIENTE {$clientNumber} ===\n";
        $logContent .= "Data: " . date('d/m/Y H:i:s') . "\n";
        $logContent .= "Cliente: " . ($customer['nome'] ?? 'N/A') . "\n";
        $logContent .= "Email: " . ($customer['email'] ?? 'N/A') . "\n";
        $logContent .= "Telefone: " . ($customer['telefone'] ?? 'N/A') . "\n";
        $logContent .= "CPF: " . ($customer['cpf'] ?? 'N/A') . "\n";
        $logContent .= "Produto: " . ($simulation['produto_nome'] ?? 'N/A') . "\n";
        $logContent .= "Valor Total: R$ " . number_format($clientData['produto']['preco'], 2, ',', '.') . "\n";
        $logContent .= "Entrada: R$ " . number_format($clientData['financiamento']['entrada'], 2, ',', '.') . "\n";
        $logContent .= "Parcelas: " . $clientData['financiamento']['parcelas'] . "x de R$ " . number_format($clientData['financiamento']['valor_parcela'], 2, ',', '.') . "\n";
        $logContent .= "Melhor Dia: " . $clientData['financiamento']['melhor_dia'] . "\n";
        $logContent .= "Status: Aguardando Pagamento\n";
        $logContent .= "===========================================\n";
        
        file_put_contents($logFile, $logContent);
        
        // Criar README
        $readmeFile = $clientDir . '/README.txt';
        $readmeContent = "PASTA DO CLIENTE {$clientNumber}\n";
        $readmeContent .= "========================\n\n";
        $readmeContent .= "Esta pasta contém todos os dados do cliente que finalizou a compra.\n\n";
        $readmeContent .= "Arquivos:\n";
        $readmeContent .= "- dados_cliente.json: Dados completos do pedido em formato JSON\n";
        $readmeContent .= "- log_pedido.txt: Log resumido do pedido\n";
        $readmeContent .= "- README.txt: Este arquivo explicativo\n";
        
        if ($clientData['documentos']['tipo'] === 'rg') {
            $readmeContent .= "- rg_frente.*: Foto do RG frente (se enviada)\n";
            $readmeContent .= "- rg_verso.*: Foto do RG verso (se enviada)\n";
        } elseif ($clientData['documentos']['tipo'] === 'cnh_fisica') {
            $readmeContent .= "- cnh_frente.*: Foto da CNH frente (se enviada)\n";
            $readmeContent .= "- cnh_verso.*: Foto da CNH verso (se enviada)\n";
        } elseif ($clientData['documentos']['tipo'] === 'cnh_digital') {
            $readmeContent .= "- cnh_digital.pdf: PDF da CNH Digital (se enviado)\n";
        }
        
        $readmeContent .= "\nData de criação: " . date('d/m/Y H:i:s') . "\n";
        
        file_put_contents($readmeFile, $readmeContent);
        
        return 'cliente' . $clientNumber;
        
    } catch (Exception $e) {
        error_log("Erro ao salvar dados do cliente: " . $e->getMessage());
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(file_get_contents('php://input'))) {
    session_start();
    header('Content-Type: application/json');
    
    try {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!$data) {
            throw new Exception('Dados inválidos recebidos');
        }
        
        $clientFolder = saveClientData($data);
        
        if ($clientFolder) {
            echo json_encode([
                'success' => true,
                'message' => 'Dados do cliente salvos com sucesso',
                'clientFolder' => $clientFolder
            ]);
        } else {
            throw new Exception('Erro ao salvar dados do cliente');
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
