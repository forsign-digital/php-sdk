# Documentação Completa do SDK ForSign para PHP (v2)

Bem-vindo à documentação oficial do SDK ForSign para PHP! A ForSign oferece a integração mais simples do mercado para assinaturas eletrônicas, tornando seus processos mais eficientes, seguros e amigáveis.

Este SDK foi projetado para ser intuitivo e poderoso, permitindo que você integre um fluxo de assinatura digital completo em sua aplicação PHP com o mínimo de esforço, desde o upload de documentos até o gerenciamento final das operações.

## ✨ Funcionalidades Principais

- **Autenticação Simplificada:** Conecte-se à nossa API usando apenas uma **API Key**.
- **Múltiplos Tipos de Assinatura:** Suporte para clique, desenho, rubrica, certificado digital e carimbos personalizados.
- **Segurança Robusta:** Valide a identidade dos Signatários com **duplo fator de autenticação (2FA)** via E-mail, SMS e WhatsApp.
- **Workflows Flexíveis:** Defina a ordem de assinatura, datas de expiração e redirecionamento pós-assinatura.
- **Formulários Interativos:** Colete dados estruturados diretamente no documento com campos de texto, checkboxes e listas de seleção.
- **Anexos Seguros:** Solicite documentos adicionais (como RG, CNH) dos Signatários durante o processo.
- **Gerenciamento Completo:** Tenha controle total sobre o ciclo de vida das operações: finalize, cancele, baixe documentos e gerencie anexos.
- **Posicionamento de Assinatura Inteligente:** Posicione assinaturas e rubricas com coordenadas precisas ou usando **tags de texto** (ex: `{{assinatura_cliente}}`) diretamente no seu documento PDF.

---

## ⚙️ 1. Começando

Siga estes passos para instalar, configurar e criar sua primeira operação de assinatura.

### 1.1. Requisitos

- **PHP:** 8.1 ou superior
- **Composer:** Para gerenciar as dependências do projeto.
- **API Key da ForSign:** Você pode obter sua chave no painel de desenvolvedor da ForSign.

### 1.2. Instalação via Composer

Instale o SDK em seu projeto com um único comando:

```bash
composer require forsign/api-php
```

### 1.3. Configuração e Autenticação

A autenticação é feita de forma simples através da sua API Key. Instancie o cliente `ForSign\Api\Client` passando a chave no construtor.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use ForSign\Api\Client;

// Substitua 'SUA_API_KEY_AQUI' pela sua chave de API real
$apiKey = 'SUA_API_KEY_AQUI';

// Crie uma instância do cliente
$client = new Client($apiKey);

// Opcional: Para ambientes de teste ou homologação, você pode especificar a URL base.
// $client = new Client($apiKey, ['baseUri' => 'https://homolog.forsign.digital']);
```

### 1.4. Sua Primeira Operação (Exemplo Rápido)

Este é um exemplo completo e funcional para criar uma operação de assinatura.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use ForSign\Api\Client;
use ForSign\Api\Enums\Language;
use ForSign\Api\Enums\SignatureType;
use ForSign\Api\Requests\FileInformation;
use ForSign\Api\Requests\Signer;
use ForSign\Api\NotificationTypes\EmailNotification;
use ForSign\Api\AuthenticationTypes\EmailDoubleAuthentication;
use ForSign\Api\SignatureTypes\DefaultSignatureType;

try {
    // 1. Configure o Cliente
    $client = new Client('SUA_API_KEY_AQUI');

    // 2. Faça o Upload do Documento
    echo "Fazendo upload do documento...\n";
    $filePath = __DIR__ . '/contrato.pdf'; // Certifique-se que este arquivo exista
    $uploadResponse = $client->uploadFile($filePath);
    $fileInfo = new FileInformation($uploadResponse->getId(), $uploadResponse->getFileName());
    echo "Documento enviado! ID: " . $fileInfo->getFileId() . "\n";

    // 3. Defina um Signatário
    $signer = new Signer();
    $signer->setName('João da Silva')
           ->setEmail('joao.silva@example.com')
           ->setPhone('11987654321')
           ->setRole('Contratante')
           ->setNotificationType(new EmailNotification('joao.silva@example.com'))
           ->setDoubleAuthenticationMethod(new EmailDoubleAuthentication('joao.silva@example.com'))
           ->setSignatureType(new DefaultSignatureType(SignatureType::Draw));

    // 4. Posicione a Assinatura no Documento
    // Adiciona uma assinatura na página 1, a 70% da largura e 80% da altura.
    $signer->addSignatureInPosition($fileInfo, 1, '70%', '80%');

    // 5. Crie a Operação usando o OperationBuilder
    echo "Criando a operação...\n";
    $operationRequest = $client->createOperationBuilder('Contrato de Serviço - PHP SDK')
        ->setLanguage(Language::Portuguese)
        ->addSigner($signer)
        ->build();

    // 6. Envie a Requisição para a API
    $response = $client->operations()->create($operationRequest);

    // 7. Exiba os Resultados
    echo "Operação criada com sucesso! ID: " . $response->getId() . "\n";
    foreach ($response->getMembers() as $member) {
        echo "-> URL de Assinatura para {$member['name']}: {$member['signUrl']}\n";
    }

} catch (\Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    if ($e instanceof \ForSign\Api\Exceptions\ValidationException) {
        echo "Erros de validação:\n";
        foreach ($e->getValidationErrors() as $field => $error) {
            echo "  - {$field}: {$error}\n";
        }
    }
}
```

---

## 📚 2. Guia de Uso Detalhado

Esta seção cobre em detalhes as principais funcionalidades do SDK.

### 2.1. Upload de Documentos

Antes de criar uma operação, você precisa enviar os documentos para a ForSign.

- **Método:** `$client->uploadFile(string $filePath): DocumentUploadResponse`
- **Retorno:** Um objeto `DocumentUploadResponse` com o ID e nome do arquivo.
- **Importante:** Apenas arquivos PDF são suportados.

Após o upload, você deve criar um objeto `FileInformation` para referenciar o documento em outras partes do SDK (como no posicionamento de assinaturas e formulários).

```php
use ForSign\Api\Requests\FileInformation;

$uploadResponse = $client->uploadFile('/caminho/para/seu/documento.pdf');
$fileInfo = new FileInformation(
    $uploadResponse->getId(), 
    $uploadResponse->getFileName()
);
```

### 2.2. Criando uma Operação com `OperationBuilder`

O `OperationBuilder` oferece uma interface fluente para construir o objeto de requisição da operação.

```php
$operationRequest = $client->createOperationBuilder('Nome da Operação')
    // Define a data de expiração da operação (opcional)
    ->setExpirationDate((new DateTime())->add(new DateInterval('P30D'))) 
    // Exige que os signatários assinem na ordem em que foram adicionados
    ->setSignersOrderRequirement(true)
    // ID externo para sua referência interna (opcional)
    ->withExternalId('ID-INTERNO-123')
    // URL para onde o signatário será redirecionado após assinar (opcional)
    ->withRedirectUrl('https://seusite.com/obrigado/{operationId}/{externalId}')
    // Adiciona um signatário configurado
    ->addSigner($signer1)
    // Adiciona outro signatário
    ->addSigner($signer2)
    // Define o idioma da interface para o signatário
    ->setLanguage(Language::Portuguese)
    // Constrói o objeto de requisição final
    ->build();

$response = $client->operations()->create($operationRequest);
```

### 2.3. Configurando Signatários (`Signer`)

O objeto `Signer` centraliza todas as configurações de um participante.

#### Informações Básicas
```php
use ForSign\Api\Requests\Signer;

$signer = new Signer();
$signer->setName('Maria Oliveira')
       ->setEmail('maria.oliveira@example.com')
       ->setPhone('11912345678')
       ->setRole('Testemunha');
```

#### Autenticação Dupla (2FA)
Aumente a segurança exigindo uma segunda forma de validação.

```php
use ForSign\Api\AuthenticationTypes\EmailDoubleAuthentication;
use ForSign\Api\AuthenticationTypes\SmsDoubleAuthentication;
use ForSign\Api\AuthenticationTypes\WhatsappDoubleAuthentication;

// Por E-mail
$signer->setDoubleAuthenticationMethod(new EmailDoubleAuthentication('maria.oliveira@example.com'));

// Por SMS (requer que o telefone tenha sido definido no Signer)
$signer->setDoubleAuthenticationMethod(new SmsDoubleAuthentication('11912345678'));

// Por WhatsApp (requer que o telefone tenha sido definido no Signer)
$signer->setDoubleAuthenticationMethod(new WhatsappDoubleAuthentication('11912345678'));
```

#### Tipos de Assinatura
Escolha como o signatário irá assinar o documento.

```php
use ForSign\Api\Enums\SignatureType;
use ForSign\Api\SignatureTypes\DefaultSignatureType;

// O signatário desenha a assinatura
$signer->setSignatureType(new DefaultSignatureType(SignatureType::Draw));

// O signatário pode escolher entre as opções disponíveis (desenho, texto, etc.)
$signer->setSignatureType(new DefaultSignatureType(SignatureType::UserChoice));

// A assinatura é um simples clique (aceite)
$signer->setSignatureType(new DefaultSignatureType(SignatureType::Click));
```
Para a lista completa, veja a referência do `Enum SignatureType`.

#### Notificações
Defina como o signatário será notificado.

```php
use ForSign\Api\NotificationTypes\EmailNotification;

$signer->setNotificationType(new EmailNotification('maria.oliveira@example.com'));
```

### 2.4. Posicionando Assinaturas e Rubricas

Você pode definir exatamente onde a assinatura ou rubrica deve aparecer no PDF.

#### a) Posicionamento por Coordenadas

Use percentuais para definir a posição (X, Y) em uma página específica.

```php
// Assinatura na página 1, 50% da largura, 60% da altura
$signer->addSignatureInPosition($fileInfo, 1, '50%', '60%');

// Rubrica na página 2, 85% da largura, 95% da altura (canto inferior direito)
$signer->addRubricInPosition($fileInfo, 2, '85%', '95%');
```

#### b) Posicionamento por Tag

Este é o método mais flexível. Adicione um texto padrão (uma "tag") no seu documento PDF, como `{{assinatura_cliente}}`. O SDK irá encontrar essa tag e posicionar a assinatura sobre ela.

```php
use ForSign\Api\SignaturePositionTypes\TagPosition;

// O documento 'contrato_com_tag.pdf' deve conter o texto "{{assinatura_cliente}}"
$tagPosition = new TagPosition($fileInfo, '{{assinatura_cliente}}');
$signer->setTagSignaturePosition($tagPosition);
```

### 2.5. Campos de Formulário

Colete informações dos signatários diretamente nos documentos. Os campos são adicionados a um objeto `Signer`.

#### Campo de Texto (`TextFormField`)
```php
use ForSign\Api\Forms\TextFormField;
use ForSign\Api\Forms\FormFieldPosition;

$formField = TextFormField::withName('CPF')
    ->withInstructions('Digite seu CPF')
    ->isRequired()
    ->onPosition(new FormFieldPosition($fileInfo, 1, '30%', '50%'))
    ->withValue('123.456.789-00'); // Opcional: pré-preenche o valor

$signer->addFormField($formField);
```

#### Caixa de Seleção (`CheckboxFormField`)
```php
use ForSign\Api\Forms\CheckboxFormField;

$checkbox = CheckboxFormField::withName('Aceito os termos')
    ->isRequired()
    ->withOptions(['Sim']) // Opções do checkbox
    ->onPosition(new FormFieldPosition($fileInfo, 1, '30%', '60%'));

$signer->addFormField($checkbox);
```

#### Lista Suspensa (`SelectFormField`)
```php
use ForSign\Api\Forms\SelectFormField;

$select = SelectFormField::withName('Estado Civil')
    ->isRequired()
    ->withOptions(['Solteiro(a)', 'Casado(a)', 'Divorciado(a)'])
    ->onPosition(new FormFieldPosition($fileInfo, 1, '30%', '70%'));

$signer->addFormField($select);
```

### 2.6. Solicitando Anexos

Peça para o signatário enviar arquivos adicionais, como documentos de identidade.

```php
use ForSign\Api\Requests\Attachment;
use ForSign\Api\Types\AttachmentFileType;
use ForSign\Api\Enums\InputAttachmentType;

$idAttachment = new Attachment(
    'Documento de Identidade', // Nome do anexo
    'Por favor, envie uma foto do seu RG ou CNH (frente e verso).', // Descrição
    true // É obrigatório?
);

// Define os tipos de arquivo permitidos
$idAttachment->permitFileType(AttachmentFileType::PDF())
             ->permitFileType(AttachmentFileType::JPG())
             ->permitFileType(AttachmentFileType::PNG());

// Define as formas de envio permitidas
$idAttachment->permitAttachmentByInput(InputAttachmentType::UploadFile)
             ->permitAttachmentByInput(InputAttachmentType::CameraSideFront);

$signer->requestAttachment($idAttachment);
```

### 2.7. Gerenciando o Ciclo de Vida da Operação

Todas as ações de gerenciamento são acessadas através de `$client->operations()`.

#### Finalizar uma Operação Manualmente
Se a operação foi configurada para finalização manual, você pode finalizá-la assim que todos os signatários tiverem assinado.

```php
$operationId = 12345;
$response = $client->operations()->complete($operationId);

if ($response->isSuccess()) {
    echo "Operação finalizada com sucesso!";
}
```

#### Cancelar uma Operação
```php
$operationId = 12345;
$motivo = 'Documento incorreto, será enviada uma nova versão.';
$response = $client->operations()->cancel($operationId, $motivo);

if ($response->isSuccess()) {
    echo "Operação cancelada com sucesso!";
}
```

#### Definir Modo de Finalização
Você pode alterar o modo de finalização de uma operação existente.

```php
$operationId = 12345;

// Mudar para finalização automática em 7 dias
$endDate = (new DateTime())->add(new DateInterval('P7D'));
$client->operations()->setAutomaticCompletion($operationId, $endDate);

// Mudar para finalização manual
$client->operations()->setManualCompletion($operationId);
```

#### Baixar Documentos da Operação (ZIP)
Baixa um arquivo ZIP contendo todos os documentos (originais e assinados) e o termo de assinatura.

```php
$operationId = 12345;
$zipResponse = $client->operations()->downloadZip($operationId);

// Salva o arquivo em disco
$savePath = __DIR__ . '/' . $zipResponse->getName();
if ($zipResponse->saveToFile($savePath)) {
    echo "Arquivo ZIP salvo em: " . $savePath;
}
```

### 2.8. Gerenciando Anexos dos Signatários

#### Listar Anexos de um Membro
```php
$memberId = 56789; // ID do membro (retornado na criação da operação)
$attachments = $client->operations()->getMemberAttachments($memberId);

foreach ($attachments as $attachment) {
    echo "Anexo: " . $attachment->getName() . ", Status: " . $attachment->getStatus() . "\n";
}
```

#### Aprovar ou Rejeitar Anexos
```php
$operationMemberId = 56789;

// Aprovar
$attachmentIdsToApprove = [101, 102];
$client->operations()->approveAttachments($operationMemberId, $attachmentIdsToApprove);

// Rejeitar
$attachmentsToReject = [
    ['id' => 103, 'reason' => 'Documento ilegível.'],
    ['id' => 104, 'reason' => 'Foto fora do padrão exigido.'],
];
$client->operations()->rejectAttachments($operationMemberId, $attachmentsToReject);
```

#### Baixar um Anexo Individual
```php
$attachmentId = 101; // ID do arquivo do anexo
$downloadResponse = $client->operations()->downloadAttachment($attachmentId);

$savePath = __DIR__ . '/' . $downloadResponse->getFileName();
if ($downloadResponse->saveToFile($savePath)) {
    echo "Anexo salvo em: " . $savePath;
}
```

### 2.9. Tratamento de Erros

O SDK utiliza exceções para sinalizar erros. É crucial envolver suas chamadas em blocos `try-catch`.

- `ForSign\Api\Exceptions\ApiException`: Erro geral da API (ex: API Key inválida, recurso não encontrado).
- `ForSign\Api\Exceptions\ValidationException`: Ocorre quando os dados enviados são inválidos (ex: e-mail em formato incorreto).

```php
try {
    // ... seu código que chama a API ...
} catch (\ForSign\Api\Exceptions\ValidationException $e) {
    echo "Erro de Validação: " . $e->getMessage() . "\n";
    echo "Status Code: " . $e->getStatusCode() . "\n";
    
    // Obtenha detalhes dos campos com erro
    $errors = $e->getValidationErrors();
    foreach ($errors as $field => $message) {
        echo " - Campo '{$field}': {$message}\n";
    }

} catch (\ForSign\Api\Exceptions\ApiException $e) {
    echo "Erro na API: " . $e->getMessage() . "\n";
    echo "Status Code: " . $e->getStatusCode() . "\n";

} catch (\Exception $e) {
    // Erros gerais (ex: problema de conexão)
    echo "Erro Inesperado: " . $e->getMessage() . "\n";
}
```

---

## 📖 3. Referência da API

### Classes Principais

- `ForSign\Api\Client`: Classe principal para interagir com a API.
  - `__construct(?string $apiKey, array $options)`: Construtor.
  - `setCredential(CredentialInterface $credential)`: Define as credenciais.
  - `uploadFile(string $filePath)`: Envia um documento.
  - `createOperationBuilder(string $operationName)`: Inicia o builder de operações.
  - `operations()`: Retorna o repositório para gerenciar operações.

- `ForSign\Api\Builders\OperationBuilder`: Interface fluente para criar operações.
  - `addSigner(Signer $signer)`: Adiciona um signatário.
  - `setExpirationDate(DateTime $date)`: Define a data de expiração.
  - `setSignersOrderRequirement(bool $isOrdered)`: Define se a ordem de assinatura é obrigatória.
  - `withExternalId(string $id)`: Adiciona um ID externo.
  - `withRedirectUrl(string $url)`: Define a URL de redirecionamento.
  - `setLanguage(Language $lang)`: Define o idioma.
  - `build()`: Retorna o objeto `OperationRequest`.

- `ForSign\Api\Requests\Signer`: Representa um participante da operação.
  - `setName(string $name)`, `setEmail(string $email)`, etc.
  - `setDoubleAuthenticationMethod(DoubleAuthenticationInterface $auth)`
  - `setNotificationType(NotificationInterface $notification)`
  - `setSignatureType(SignatureInformationType $type)`
  - `addSignatureInPosition(...)`
  - `addRubricInPosition(...)`
  - `setTagSignaturePosition(TagPosition $tag)`
  - `addFormField(FormFieldInterface $field)`
  - `requestAttachment(Attachment $attachment)`

- `ForSign\Api\Repositories\OperationRepository`: Agrupa os métodos de gerenciamento.
  - `create(OperationRequest $request)`
  - `complete(int $operationId)`
  - `cancel(int $operationId, string $message)`
  - `setAutomaticCompletion(int $operationId, DateTime $endDate)`
  - `setManualCompletion(int $operationId)`
  - `downloadZip(int $operationId)`
  - `getMemberAttachments(int $memberId)`
  - `approveAttachments(int $opMemberId, array $attachmentIds)`
  - `rejectAttachments(int $opMemberId, array $rejectedAttachments)`
  - `downloadAttachment(int $attachmentId)`

### Enums Importantes

- `ForSign\Api\Enums\Language`:
  - `Language::Portuguese` ('pt-br')
  - `Language::English` ('en-us')
  - `Language::Spanish` ('es-es')

- `ForSign\Api\Enums\SignatureType`:
  - `SignatureType::Click`
  - `SignatureType::Draw`
  - `SignatureType::Text`
  - `SignatureType::Stamp`
  - `SignatureType::UserChoice`
  - `SignatureType::Rubric`
  - `SignatureType::Certificate`

- `ForSign\Api\Enums\InputAttachmentType`:
  - `InputAttachmentType::CameraSideBack`
  - `InputAttachmentType::CameraSideFront`
  - `InputAttachmentType::UploadFile`

- `ForSign\Api\Types\AttachmentFileType`:
  - `AttachmentFileType::PDF()`
  - `AttachmentFileType::PNG()`
  - `AttachmentFileType::JPG()`
  - `AttachmentFileType::JPEG()`
  - `AttachmentFileType::TIFF()`