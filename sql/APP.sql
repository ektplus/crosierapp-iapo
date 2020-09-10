START TRANSACTION;

-- app                   7102f854-c209-11ea-b7e0-932eff4dee21


SET FOREIGN_KEY_CHECKS=0;

DELETE FROM cfg_app WHERE uuid = '7102f854-c209-11ea-b7e0-932eff4dee21';



INSERT INTO `cfg_app` (`uuid`, `nome`, `obs`, `inserted`, `updated`, `estabelecimento_id`, `user_inserted_id`, `user_updated_id`)
VALUES ('7102f854-c209-11ea-b7e0-932eff4dee21','crosierapp-iapo','CrosierApp Viação Iapó',now(),now(),1,1,1);


COMMIT;
