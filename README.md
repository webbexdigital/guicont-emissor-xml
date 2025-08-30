# Guia Cont

Sistema de gerenciamento e download de Notas Fiscais Eletrônicas (NF-e).

## Funcionalidades da versão paga:

1.  Área para comprar versão paga;
2.  Integrar com o Asaas para o usuário fazer o cadrasto e comprar o serviço;
3.  Download em Lote;
4.  Buscar NFs sem usar extensão de navegador;
5.  Implementar listagem das NFs/XMLs emitidas por um CNPJ.

---

## 🏛️ Esquema do Banco de Dados

O sistema é projetado para gerenciar e processar Notas Fiscais Eletrônicas (NF-e), além de controlar o acesso dos usuários através de um sistema de pagamentos. O esquema foi escrito e validado para ser compatível com **MySQL**.

### Tabelas Principais

O banco de dados é organizado em torno de algumas entidades centrais:

*   **Usuários e Empresas**:
    *   `usuarios`: Gerencia as informações de login e o status da assinatura.
    *   `empresas`: Armazena os dados das empresas vinculadas a um usuário.
    *   `enderecos`: Tabela polimórfica para endereços de usuários e empresas.

*   **Pagamentos e Assinaturas**:
    *   `pagamentos`: Registra as transações financeiras e o status das assinaturas processadas por um gateway de pagamento.

*   **Dados e Processamento de NF-e**:
    *   `chaves_acesso`: Armazena as chaves de API para comunicação com a SEFAZ.
    *   `xml_downloads`: Guarda os arquivos XML brutos baixados.
    *   `xml_nfe_detalhes`: Tabela detalhada com os dados extraídos e estruturados das NF-es, permitindo consultas complexas sobre emitente, destinatário, produtos e impostos.

### Resumo da Estrutura

A arquitetura permite que usuários gerenciem múltiplas empresas, baixem NF-es em lote da SEFAZ e controlem suas assinaturas. A estrutura é otimizada para armazenar e consultar de forma eficiente os dados detalhados das notas fiscais, transformando o XML em um formato relacional e pesquisável.
