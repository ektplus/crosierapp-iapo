START TRANSACTION;

-- app                   ce60006c-4fc9-4012-a96c-94174709723e
-- program               e6e9863d-61cd-47a9-8e7b-654fd3922776
-- entMenu (CrosierCore) bf80f720-ac8e-47bd-9a0f-9e7c33dce84c
-- entMenu (Raíz do App) 8a01fa79-d5d1-487d-979a-508cc9b7a3ac
-- entMenu (Dashboard)   ceea8fca-05db-40d8-b2c5-6cd66d93c221

SET FOREIGN_KEY_CHECKS=0;

DELETE FROM cfg_app WHERE uuid = 'ce60006c-4fc9-4012-a96c-94174709723e';
DELETE FROM cfg_program WHERE uuid = 'e6e9863d-61cd-47a9-8e7b-654fd3922776';
DELETE FROM cfg_entmenu WHERE uuid = 'bf80f720-ac8e-47bd-9a0f-9e7c33dce84c';
DELETE FROM cfg_entmenu WHERE uuid = '8a01fa79-d5d1-487d-979a-508cc9b7a3ac';
DELETE FROM cfg_entmenu WHERE uuid = 'ceea8fca-05db-40d8-b2c5-6cd66d93c221';


INSERT INTO cfg_app(uuid,nome,obs,default_entmenu_uuid,inserted,updated,estabelecimento_id,user_inserted_id,user_updated_id) 
VALUES ('ce60006c-4fc9-4012-a96c-94174709723e','crosierapp-iapo','CrosierApp para a Iapó','8a01fa79-d5d1-487d-979a-508cc9b7a3ac',now(),now(),1,1,1);

INSERT INTO cfg_program(uuid, descricao, url, app_uuid, entmenu_uuid ,inserted, updated, estabelecimento_id, user_inserted_id, user_updated_id)
VALUES ('e6e9863d-61cd-47a9-8e7b-654fd3922776','crosierapp-iapo - Dashboard', '/', 'ce60006c-4fc9-4012-a96c-94174709723e', null, now(), now(), 1, 1, 1);



-- Entrada de menu para o MainMenu do Crosier com apontamento para o Dashboard deste CrosierApp (É EXIBIDO NO MENU DO CROSIER-CORE)
INSERT INTO cfg_entmenu(uuid,label,icon,tipo,program_uuid,pai_uuid,ordem,css_style,inserted,updated,estabelecimento_id,user_inserted_id,user_updated_id)
VALUES ('bf80f720-ac8e-47bd-9a0f-9e7c33dce84c','Iapó','fas fa-columns','CROSIERCORE_APPENT','e6e9863d-61cd-47a9-8e7b-654fd3922776',null,0,null,now(),now(),1,1,1);

-- Entrada de menu raíz para este CrosierApp (NÃO É EXIBIDO)
INSERT INTO cfg_entmenu(uuid,label,icon,tipo,program_uuid,pai_uuid,ordem,css_style,inserted,updated,estabelecimento_id,user_inserted_id,user_updated_id)
VALUES ('8a01fa79-d5d1-487d-979a-508cc9b7a3ac','crosierapp-iapo (MainMenu)','','PAI','',null,0,null,now(),now(),1,1,1);

-- Entrada de menu para o menu raíz deste CrosierApp com apontamento para o Dashboard deste CrosierApp TAMBÉM! (É EXIBIDO COMO PRIMEIRO ITEM DO MENU DESTE CROSIERAPP)
INSERT INTO cfg_entmenu(uuid,label,icon,tipo,program_uuid,pai_uuid,ordem,css_style,inserted,updated,estabelecimento_id,user_inserted_id,user_updated_id)
VALUES ('ceea8fca-05db-40d8-b2c5-6cd66d93c221','Dashboard','fas fa-columns','ENT','e6e9863d-61cd-47a9-8e7b-654fd3922776','8a01fa79-d5d1-487d-979a-508cc9b7a3ac',0,null,now(),now(),1,1,1);



COMMIT;
