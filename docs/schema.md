# Esquema do Banco de Dados

O sistema é projetado para gerenciar e processar Notas Fiscais Eletrônicas (NF-e) brasileiras, além de controlar o acesso dos usuários através de um sistema de pagamentos.

O esquema foi escrito e validado para ser compatível com **MySQL**.

## Diagrama de Entidade e Relacionamento (DER)

O diagrama abaixo ilustra as principais tabelas e seus relacionamentos.

```mermaid
erDiagram
    usuarios {
        id INT PK
        nome VARCHAR
        email VARCHAR
        assinante BOOLEAN
    }
    empresas {
        id INT PK
        usuario_id INT FK
        nome_fantasia VARCHAR
        cnpj VARCHAR
    }
    enderecos {
        id INT PK
        tipo_entidade ENUM
        entidade_id INT
        logradouro VARCHAR
    }
    pagamentos {
        id INT PK
        usuario_id INT FK
        gateway_id VARCHAR
        status VARCHAR
    }
    chaves_acesso {
        id INT PK
        empresa_id INT FK
        ativo BOOLEAN
    }
    xml_downloads {
        id INT PK
        empresa_id INT FK
        chave_acesso_id INT FK
        status VARCHAR
    }
    xml_nfe_detalhes {
        id INT PK
        xml_id INT FK
        chave_acesso VARCHAR
        numero_nota VARCHAR
    }

    usuarios ||--o{ empresas : "possui"
    usuarios ||--o{ pagamentos : "realiza"
    empresas ||--o{ chaves_acesso : "possui"
    empresas ||--o{ xml_downloads : "gera"
    chaves_acesso ||--o{ xml_downloads : "é usada em"
    xml_downloads ||--|| xml_nfe_detalhes : "é detalhado por"

    subgraph "Endereço Polimórfico"
        usuarios --o{ enderecos : "pode ter"
        empresas --o{ enderecos : "pode ter"
    end
```
**Nota sobre o relacionamento polimórfico:** A tabela `enderecos` pode se relacionar tanto com `usuarios` quanto com `empresas`, o que é indicado pela coluna `tipo_entidade`.

## Tabelas Principais

### 1. Usuários e Empresas

*   **`usuarios`**: Armazena as informações de login dos usuários. E inclui um campo `assinante` (`BOOLEAN`).
*   **`empresas`**: Armazena os dados das empresas, que são sempre vinculadas a um `usuario`. Um usuário pode ter várias empresas.

### 2. Endereços

*   **`enderecos`**: Tabela polimórfica que armazena endereços tanto para `usuarios` quanto para `empresas`. A coluna `tipo_entidade` (`ENUM`) diferencia o proprietário do endereço.

### 3. Pagamentos e Assinaturas

*   **`pagamentos`**: Armazena os dados das transações financeiras (cobranças, pagamentos, assinaturas) processadas por um gateway de pagamento (ex: Asaas).
    *   Relaciona-se diretamente com a tabela `usuarios`.
    *   Campos importantes incluem `gateway_id` (ID da transação externa), `status` (ex: `PAID`, `PENDING`), `valor` e `forma_pagamento`.

### 4. Acesso e Downloads de NF-e

*   **`chaves_acesso`**: Guarda as chaves de API para comunicação com a SEFAZ (Secretaria da Fazenda), essenciais para o download das notas fiscais.
*   **`xml_downloads`**: Registra os downloads de arquivos XML de NF-e.
    *   Armazena o conteúdo do arquivo XML em um campo `LONGBLOB` (`arquivo_xml`).
    *   Associa o download a uma `empresa` e à `chave_acesso` utilizada.

### 5. Detalhes da NF-e

*   **`xml_nfe_detalhes`**: A tabela mais detalhada do esquema. Ela armazena os dados extraídos e parseados dos arquivos XML. Isso transforma os dados da NF-e de um formato não estruturado (XML) para um formato relacional e pesquisável.
*   Esta tabela inclui seções para:
    *   Dados do Emitente
    *   Dados do Destinatário
    *   Informações de Produtos
    *   Múltiplos impostos (ICMS, IPI, II, PIS, COFINS)
    *   Totais da nota

## Resumo da Estrutura

O esquema define a aplicação que permite aos usuários gerenciar suas empresas, baixar NF-es da SEFAZ e controlar o status de suas assinaturas através de uma tabela de pagamentos. A estrutura é otimizada para armazenar e consultar de forma eficiente os dados detalhados das notas fiscais.