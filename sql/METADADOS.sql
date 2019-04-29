DROP TABLE IF EXISTS `ip_rh_refeicao`;


CREATE TABLE `ip_rh_refeicao` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `colaborador_id` bigint(20) NOT NULL,
  `data` date DEFAULT NULL,
  `qtde` int(11) DEFAULT NULL,
  `almoco` int(1) DEFAULT NULL,
  `jantar` int(1) DEFAULT NULL,
  `cafe_manha` int(1) DEFAULT NULL,
  `cafe_tarde` int(1) DEFAULT NULL,

  `updated` datetime DEFAULT NULL,
  `inserted` datetime DEFAULT NULL,
  `user_inserted_id` bigint(20) DEFAULT NULL,
  `user_updated_id` bigint(20) DEFAULT NULL,
  `estabelecimento_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_ip_rh_refeicao` (`data`,`colaborador_id`),

  KEY `K_ip_rh_refeicao_colaborador` (`colaborador_id`),
  KEY `K_ip_rh_refeicao_estabelecimento` (`estabelecimento_id`),
  KEY `K_ip_rh_refeicao_inserted` (`user_inserted_id`),
  KEY `K_ip_rh_refeicao_updated` (`user_updated_id`),
  CONSTRAINT `FK_ip_rh_refeicao_colaborador` FOREIGN KEY (`colaborador_id`) REFERENCES `rh_colaborador` (`id`),
  CONSTRAINT `FK_ip_rh_refeicao_updated` FOREIGN KEY (`user_updated_id`) REFERENCES `sec_user` (`id`),
  CONSTRAINT `FK_ip_rh_refeicao_inserted` FOREIGN KEY (`user_inserted_id`) REFERENCES `sec_user` (`id`),
  CONSTRAINT `FK_ip_rh_refeicao_estabelecimento` FOREIGN KEY (`estabelecimento_id`) REFERENCES `cfg_estabelecimento` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
