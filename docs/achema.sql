CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  sobrenome VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL,
  assinante BOOLEAN DEFAULT FALSE,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE empresas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  nome_fantasia VARCHAR(255) NOT NULL,
  razao_social VARCHAR(255) NOT NULL,
  cnpj VARCHAR(20) UNIQUE NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE enderecos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tipo_entidade ENUM('usuario', 'empresa') NOT NULL,
  entidade_id INT NOT NULL,
  logradouro VARCHAR(255) NOT NULL,
  numero VARCHAR(20) NOT NULL,
  complemento VARCHAR(255),
  bairro VARCHAR(255) NOT NULL,
  cidade VARCHAR(255) NOT NULL,
  estado VARCHAR(2) NOT NULL,
  cep VARCHAR(10) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  -- Constraint to ensure entidade_id corresponds to the right table based on tipo_entidade can be implemented at application level
  INDEX (tipo_entidade, entidade_id)
);

CREATE TABLE chaves_acesso (
  id INT AUTO_INCREMENT PRIMARY KEY,
  empresa_id INT NOT NULL,
  chave_acesso_api_sefaz VARCHAR(100) NOT NULL,
  ativo BOOLEAN DEFAULT TRUE,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_empresa_chave FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE
);

CREATE TABLE xml_downloads (
  id INT AUTO_INCREMENT PRIMARY KEY,
  empresa_id INT NOT NULL,
  chave_acesso_id INT NOT NULL,
  arquivo_xml LONGBLOB NOT NULL,
  data_download TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  status VARCHAR(50),
  CONSTRAINT fk_empresa_xml FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
  CONSTRAINT fk_chave_xml FOREIGN KEY (chave_acesso_id) REFERENCES chaves_acesso(id) ON DELETE CASCADE
);

CREATE TABLE xml_nfe_detalhes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  xml_id INT NOT NULL, -- FK para xml_downloads
  numero_nota VARCHAR(20),
  chave_acesso VARCHAR(50) UNIQUE,
  data_emissao TIMESTAMP,
  modelo VARCHAR(10),
  serie VARCHAR(10),
  natureza_operacao VARCHAR(255),
  tipo_operacao INT,

  -- Emitente
  emitente_cnpj VARCHAR(20),
  emitente_cpf VARCHAR(20),
  emitente_razao_social VARCHAR(255),
  emitente_nome_fantasia VARCHAR(255),
  emitente_logradouro VARCHAR(255),
  emitente_numero VARCHAR(20),
  emitente_complemento VARCHAR(255),
  emitente_bairro VARCHAR(255),
  emitente_municipio VARCHAR(255),
  emitente_uf VARCHAR(2),
  emitente_cep VARCHAR(10),
  emitente_telefone VARCHAR(20),
  emitente_ie VARCHAR(50),
  emitente_iest VARCHAR(50),
  emitente_im VARCHAR(50),
  emitente_regime_tributario VARCHAR(50),

  -- Destinatário
  destinatario_cnpj VARCHAR(20),
  destinatario_cpf VARCHAR(20),
  destinatario_razao_social VARCHAR(255),
  destinatario_logradouro VARCHAR(255),
  destinatario_numero VARCHAR(20),
  destinatario_complemento VARCHAR(255),
  destinatario_bairro VARCHAR(255),
  destinatario_municipio VARCHAR(255),
  destinatario_uf VARCHAR(2),
  destinatario_cep VARCHAR(10),
  destinatario_telefone VARCHAR(20),
  destinatario_ie VARCHAR(50),
  destinatario_im VARCHAR(50),

  -- Produto
  produto_codigo VARCHAR(50),
  produto_descricao TEXT,
  produto_ncm VARCHAR(20),
  produto_cfop VARCHAR(20),
  produto_unidade VARCHAR(20),
  produto_quantidade NUMERIC(15,4),
  produto_valor_unitario NUMERIC(15,4),
  produto_valor_total NUMERIC(15,4),
  produto_codigo_barras VARCHAR(50),

  -- Impostos - ICMS, IPI, II, PIS, COFINS (exemplo de algumas colunas principais)
  icms_origem VARCHAR(10),
  icms_cst VARCHAR(10),
  icms_aliquota NUMERIC(5,2),
  icms_base_calculo NUMERIC(15,4),
  icms_valor NUMERIC(15,4),

  ipi_enquadramento_cod VARCHAR(20),
  ipi_cst VARCHAR(10),
  ipi_aliquota NUMERIC(5,2),
  ipi_base_calculo NUMERIC(15,4),
  ipi_valor NUMERIC(15,4),

  ii_base_calculo NUMERIC(15,4),
  ii_despesas_aduaneiras NUMERIC(15,4),
  ii_valor NUMERIC(15,4),

  pis_cst VARCHAR(10),
  pis_aliquota NUMERIC(5,2),
  pis_base_calculo NUMERIC(15,4),
  pis_valor NUMERIC(15,4),

  cofins_cst VARCHAR(10),
  cofins_aliquota NUMERIC(5,2),
  cofins_base_calculo NUMERIC(15,4),
  cofins_valor NUMERIC(15,4),

  -- Totais da nota
  total_valor_produtos NUMERIC(15,4),
  total_valor_frete NUMERIC(15,4),
  total_valor_seguro NUMERIC(15,4),
  total_valor_desconto NUMERIC(15,4),
  total_valor_ii NUMERIC(15,4),
  total_valor_ipi NUMERIC(15,4),
  total_valor_pis NUMERIC(15,4),
  total_valor_cofins NUMERIC(15,4),
  total_valor_nota NUMERIC(15,4),

  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_xml_detalhes FOREIGN KEY (xml_id) REFERENCES xml_downloads(id) ON DELETE CASCADE
);

CREATE TABLE pagamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  gateway_id VARCHAR(255) UNIQUE NOT NULL, -- ID da transação no gateway (ex: Asaas)
  customer_id VARCHAR(255), -- ID do cliente no gateway
  subscription_id VARCHAR(255), -- ID da assinatura no gateway
  valor NUMERIC(10, 2) NOT NULL,
  forma_pagamento VARCHAR(50) NOT NULL, -- ex: credit_card, boleto, pix
  status VARCHAR(50) NOT NULL, -- ex: PENDING, PAID, FAILED, REFUNDED
  data_pagamento TIMESTAMP,
  data_vencimento DATE,
  link_fatura VARCHAR(255),
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_usuario_pagamento FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);