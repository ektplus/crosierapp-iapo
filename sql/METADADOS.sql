SET FOREIGN_KEY_CHECKS = 0;



DROP TABLE IF EXISTS `iapo_tur_veiculo`;

CREATE TABLE `iapo_tur_veiculo`
(
    `id`                  bigint(20)  NOT NULL AUTO_INCREMENT,

    `prefixo`             varchar(50) NOT NULL,
    `apelido`             varchar(100),
    `placa`               varchar(15) NOT NULL,
    `status`              varchar(50) NOT NULL,
    `renavan`             varchar(50) NOT NULL,
    `dt_vencto_der`       date        NOT NULL,
    `dt_vencto_antt`      date        NOT NULL,
    `dt_vencto_tacografo` date        NOT NULL,
    `dt_vencto_seguro`    date        NOT NULL,

    `obs`                 varchar(3000),


    `inserted`            datetime    NOT NULL,
    `updated`             datetime    NOT NULL,
    `version`             int(11),
    `estabelecimento_id`  bigint(20)  NOT NULL,
    `user_inserted_id`    bigint(20)  NOT NULL,
    `user_updated_id`     bigint(20)  NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY `UK_iapo_tur_veiculo_codigo` (`placa`),
    UNIQUE KEY `UK_iapo_tur_veiculo_descricao` (`renavan`),

    KEY `K_iapo_tur_veiculo_estabelecimento` (`estabelecimento_id`),
    KEY `K_iapo_tur_veiculo_user_inserted` (`user_inserted_id`),
    KEY `K_iapo_tur_veiculo_user_updated` (`user_updated_id`),
    CONSTRAINT `FK_iapo_tur_veiculo_user_inserted` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`),
    CONSTRAINT `FK_iapo_tur_veiculo_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`),
    CONSTRAINT `FK_iapo_tur_veiculo_user_updated` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`)

);



DROP TABLE IF EXISTS `iapo_tur_motorista`;

CREATE TABLE `iapo_tur_motorista`
(
    `id`                              bigint(20)   NOT NULL AUTO_INCREMENT,

    `cpf`                             varchar(11)  NOT NULL,
    `rg`                              varchar(20),
    `nome`                            varchar(250) NOT NULL,
    `cnh`                             varchar(11)  NOT NULL,
    `dt_vencto_cnh`                   date         NOT NULL,
    `dt_vencto_cart_saude`            date         NOT NULL,
    `dt_validade_curso_transp_passag` date         NOT NULL,
    `fone_fixo`                       varchar(20),
    `fone_celular`                    varchar(20),
    `fone_whatsapp`                   varchar(20),
    `fone_recados`                    varchar(20),
    `obs`                             varchar(3000),

    `inserted`                        datetime     NOT NULL,
    `updated`                         datetime     NOT NULL,
    `version`                         int(11),
    `estabelecimento_id`              bigint(20)   NOT NULL,
    `user_inserted_id`                bigint(20)   NOT NULL,
    `user_updated_id`                 bigint(20)   NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY `UK_iapo_tur_motorista_cpf` (`cpf`),

    KEY `K_iapo_tur_motorista_estabelecimento` (`estabelecimento_id`),
    KEY `K_iapo_tur_motorista_user_inserted` (`user_inserted_id`),
    KEY `K_iapo_tur_motorista_user_updated` (`user_updated_id`),
    CONSTRAINT `FK_iapo_tur_motorista_user_inserted` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`),
    CONSTRAINT `FK_iapo_tur_motorista_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`),
    CONSTRAINT `FK_iapo_tur_motorista_user_updated` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`)

);


DROP TABLE IF EXISTS `iapo_tur_agencia`;

CREATE TABLE `iapo_tur_agencia`
(
    `id`                 bigint(20)   NOT NULL AUTO_INCREMENT,

    `nome`               varchar(250) NOT NULL,
    `logradouro`         varchar(200),
    `numero`             varchar(200),
    `bairro`             varchar(120),
    `complemento`        varchar(120),
    `cidade`             varchar(120),
    `estado`             char(2),
    `cep`                varchar(9),
    `email`              varchar(250),
    `perc_comissao`      decimal(12, 5),
    `fone_fixo`          varchar(20),
    `fone_celular`       varchar(20),
    `fone_whatsapp`      varchar(20),
    `obs`                varchar(3000),

    `inserted`           datetime     NOT NULL,
    `updated`            datetime     NOT NULL,
    `version`            int(11),
    `estabelecimento_id` bigint(20)   NOT NULL,
    `user_inserted_id`   bigint(20)   NOT NULL,
    `user_updated_id`    bigint(20)   NOT NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY `UK_iapo_tur_agencia_nome` (`nome`),

    KEY `K_iapo_tur_agencia_estabelecimento` (`estabelecimento_id`),
    KEY `K_iapo_tur_agencia_user_inserted` (`user_inserted_id`),
    KEY `K_iapo_tur_agencia_user_updated` (`user_updated_id`),
    CONSTRAINT `FK_iapo_tur_agencia_user_inserted` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`),
    CONSTRAINT `FK_iapo_tur_agencia_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`),
    CONSTRAINT `FK_iapo_tur_agencia_user_updated` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`)

);



DROP TABLE IF EXISTS `iapo_tur_itinerario`;

CREATE TABLE `iapo_tur_itinerario`
(
    `id`                 bigint(20)   NOT NULL AUTO_INCREMENT,

    `cidade_origem`      varchar(250) NOT NULL,
    `estado_origem`      char(2)      NOT NULL,

    `cidade_destino`     varchar(250) NOT NULL,
    `estado_destino`     char(2)      NOT NULL,

    `veiculo_id`         bigint(20)   NOT NULL,

    `preco_min`          decimal(15, 2),
    `preco_max`          decimal(15, 2),
    `obs`                varchar(3000),

    `inserted`           datetime     NOT NULL,
    `updated`            datetime     NOT NULL,
    `version`            int(11),
    `estabelecimento_id` bigint(20)   NOT NULL,
    `user_inserted_id`   bigint(20)   NOT NULL,
    `user_updated_id`    bigint(20)   NOT NULL,


    KEY `K_iapo_tur_itinerario_veiculo` (`veiculo_id`),
    CONSTRAINT `FK_iapo_tur_itinerario_veiculo` FOREIGN KEY (`veiculo_id`) REFERENCES `iapo_tur_veiculo` (`id`),

    PRIMARY KEY (`id`),

    KEY `K_iapo_tur_itinerario_estabelecimento` (`estabelecimento_id`),
    KEY `K_iapo_tur_itinerario_user_inserted` (`user_inserted_id`),
    KEY `K_iapo_tur_itinerario_user_updated` (`user_updated_id`),
    CONSTRAINT `FK_iapo_tur_itinerario_user_inserted` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`),
    CONSTRAINT `FK_iapo_tur_itinerario_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`),
    CONSTRAINT `FK_iapo_tur_itinerario_user_updated` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`)

);



DROP TABLE IF EXISTS `iapo_tur_viagem`;

CREATE TABLE `iapo_tur_viagem`
(
    `id`                 bigint(20)  NOT NULL AUTO_INCREMENT,

    `num_pedido`         varchar(50) NOT NULL,
    `status`             varchar(50) NOT NULL,
    `dthr_saida`         datetime,
    `dthr_retorno`       datetime,

    `veiculo_id`         BIGINT(20), -- deve ser o mesmo do itinerário
    `agencia_id`         BIGINT(20),
    `motorista_id`       BIGINT(20),
    `itinerario_id`      BIGINT(20)  NOT NULL,
    `flg_financeiro`     char(1),
    `flg_contrato`       char(1),

    `valor_poltrona`     decimal(15, 2),
    `valor_taxas`        decimal(15, 2),
    `valor_bagagem`      decimal(15, 2),

    `valor`              decimal(15, 2),
    `obs`                varchar(3000),

    `inserted`           datetime    NOT NULL,
    `updated`            datetime    NOT NULL,
    `version`            int(11),
    `estabelecimento_id` bigint(20)  NOT NULL,
    `user_inserted_id`   bigint(20)  NOT NULL,
    `user_updated_id`    bigint(20)  NOT NULL,


    UNIQUE KEY `UK_iapo_tur_viagem_num_pedido` (`num_pedido`),

    KEY `K_iapo_tur_viagem_veiculo` (`veiculo_id`),
    CONSTRAINT `FK_iapo_tur_viagem_veiculo` FOREIGN KEY (`veiculo_id`) REFERENCES `iapo_tur_veiculo` (`id`),

    KEY `K_iapo_tur_viagem_agencia` (`agencia_id`),
    CONSTRAINT `FK_iapo_tur_viagem_agencia` FOREIGN KEY (`agencia_id`) REFERENCES `iapo_tur_agencia` (`id`),

    KEY `K_iapo_tur_viagem_motorista` (`motorista_id`),
    CONSTRAINT `FK_iapo_tur_viagem_motorista` FOREIGN KEY (`motorista_id`) REFERENCES `iapo_tur_motorista` (`id`),

    KEY `K_iapo_tur_viagem_itinerario` (`itinerario_id`),
    CONSTRAINT `FK_iapo_tur_viagem_itinerario` FOREIGN KEY (`itinerario_id`) REFERENCES `iapo_tur_itinerario` (`id`),


    PRIMARY KEY (`id`),

    KEY `K_iapo_tur_viagem_estabelecimento` (`estabelecimento_id`),
    KEY `K_iapo_tur_viagem_user_inserted` (`user_inserted_id`),
    KEY `K_iapo_tur_viagem_user_updated` (`user_updated_id`),
    CONSTRAINT `FK_iapo_tur_viagem_user_inserted` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`),
    CONSTRAINT `FK_iapo_tur_viagem_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`),
    CONSTRAINT `FK_iapo_tur_viagem_user_updated` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`)

);


--
DROP TABLE IF EXISTS `iapo_tur_passageiro`;

CREATE TABLE `iapo_tur_passageiro`
(
    `id`                 bigint(20)   NOT NULL AUTO_INCREMENT,

    `viagem_id`          BIGINT(20)   NOT NULL,

    `cpf`                varchar(11),
    `rg`                 varchar(20),
    `nome`               varchar(250) NOT NULL,
    `dt_nascimento`      date         NOT NULL,

    `fone_fixo`          varchar(20),
    `fone_celular`       varchar(20),
    `fone_whatsapp`      varchar(20),
    `fone_recados`       varchar(20),
    `email`              varchar(250),
    `obs`                varchar(3000),

    `inserted`           datetime     NOT NULL,
    `updated`            datetime     NOT NULL,
    `version`            int(11),
    `estabelecimento_id` bigint(20)   NOT NULL,
    `user_inserted_id`   bigint(20)   NOT NULL,
    `user_updated_id`    bigint(20)   NOT NULL,

    UNIQUE KEY UK_iapo_tur_passageiro (`viagem_id`, `cpf`, `rg`),

    PRIMARY KEY (`id`),

    KEY `K_iapo_tur_passageiro_viagem` (`viagem_id`),
    CONSTRAINT `FK_iapo_tur_passageiro_viagem` FOREIGN KEY (`viagem_id`) REFERENCES `iapo_tur_viagem` (`id`),

    KEY `K_iapo_tur_passageiro_estabelecimento` (`estabelecimento_id`),
    KEY `K_iapo_tur_passageiro_user_inserted` (`user_inserted_id`),
    KEY `K_iapo_tur_passageiro_user_updated` (`user_updated_id`),
    CONSTRAINT `FK_iapo_tur_passageiro_user_inserted` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`),
    CONSTRAINT `FK_iapo_tur_passageiro_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`),
    CONSTRAINT `FK_iapo_tur_passageiro_user_updated` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`)

);


--
DROP TABLE IF EXISTS `iapo_tur_cliente`;

CREATE TABLE `iapo_tur_cliente`
(
    `id`                 bigint(20)   NOT NULL AUTO_INCREMENT,

    `cpf`                varchar(11)  NOT NULL,
    `rg`                 varchar(20),
    `nome`               varchar(250) NOT NULL,
    `dt_nascimento`      date,

    `fone`               varchar(20),
    `celular`            varchar(20),
    `email`              varchar(250),

    `senha`              varchar(250),

    `json_data`          json,

    `inserted`           datetime     NOT NULL,
    `updated`            datetime     NOT NULL,
    `version`            int(11),
    `estabelecimento_id` bigint(20)   NOT NULL,
    `user_inserted_id`   bigint(20)   NOT NULL,
    `user_updated_id`    bigint(20)   NOT NULL,

    UNIQUE KEY UK_iapo_tur_cliente (`cpf`),

    PRIMARY KEY (`id`),

    KEY `K_iapo_tur_cliente_estabelecimento` (`estabelecimento_id`),
    KEY `K_iapo_tur_cliente_user_inserted` (`user_inserted_id`),
    KEY `K_iapo_tur_cliente_user_updated` (`user_updated_id`),
    CONSTRAINT `FK_iapo_tur_cliente_user_inserted` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`),
    CONSTRAINT `FK_iapo_tur_cliente_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`),
    CONSTRAINT `FK_iapo_tur_cliente_user_updated` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`)

);


DROP TABLE IF EXISTS `iapo_tur_compra`;

CREATE TABLE `iapo_tur_compra`
(
    `id`                 bigint(20) NOT NULL AUTO_INCREMENT,

    `dt_compra`          datetime,
    `status`             varchar(50),

    `viagem_id`          BIGINT(20),
    `cliente_id`         BIGINT(20),

    `valor_total`        decimal(15, 2),

    `json_data`          json,

    `inserted`           datetime   NOT NULL,
    `updated`            datetime   NOT NULL,
    `version`            int(11),
    `estabelecimento_id` bigint(20) NOT NULL,
    `user_inserted_id`   bigint(20) NOT NULL,
    `user_updated_id`    bigint(20) NOT NULL,


    KEY `K_iapo_tur_compra_viagem` (`viagem_id`),
    CONSTRAINT `FK_iapo_tur_compra_viagem` FOREIGN KEY (`viagem_id`) REFERENCES `iapo_tur_viagem` (`id`),

    KEY `K_iapo_tur_compra_cliente` (`cliente_id`),
    CONSTRAINT `FK_iapo_tur_compra_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `iapo_tur_cliente` (`id`),

    PRIMARY KEY (`id`),

    KEY `K_iapo_tur_compra_estabelecimento` (`estabelecimento_id`),
    KEY `K_iapo_tur_compra_user_inserted` (`user_inserted_id`),
    KEY `K_iapo_tur_compra_user_updated` (`user_updated_id`),
    CONSTRAINT `FK_iapo_tur_compra_user_inserted` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`),
    CONSTRAINT `FK_iapo_tur_compra_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`),
    CONSTRAINT `FK_iapo_tur_compra_user_updated` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`)

);
