--
-- Add new tab
--
INSERT INTO `core_form_tab` (`Tab_ID`, `form`, `title`, `subtitle`, `icon`, `counter`, `sort_id`, `filter_check`, `filter_value`) VALUES
('article-variant', 'article-single', 'Variant', 'Executions', 'fas fa-layer-group', '', '1', '', '');

--
-- Add new partial
--
INSERT INTO `core_form_field` (`Field_ID`, `type`, `label`, `fieldkey`, `tab`, `form`, `class`, `url_view`, `url_list`, `show_widget_left`, `allow_clear`, `readonly`, `tbl_cached_name`, `tbl_class`, `tbl_permission`) VALUES
(NULL, 'partial', 'Variant', 'article_variant', 'article-variant', 'article-single', 'col-md-12', '', '', '0', '1', '0', '', '', '');

--
-- add button
--
INSERT INTO `core_form_button` (`Button_ID`, `label`, `icon`, `title`, `href`, `class`, `append`, `form`, `mode`, `filter_check`, `filter_value`) VALUES
(NULL, 'Add Variant', 'fas fa-layer-group', 'Add Variant', '/article/variant/add/##ID##', 'primary', '', 'article-view', 'link', '', ''),
(NULL, 'Save Variant', 'fas fa-save', 'Save Variant', '#', 'primary saveForm', '', 'articlevariant-single', 'link', '', '');

--
-- create variant table
--
CREATE TABLE `article_variant` (
  `Variant_ID` int(11) NOT NULL,
  `article_idfs` int(11) NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `article_variant`
  ADD PRIMARY KEY (`Variant_ID`);

ALTER TABLE `article_variant`
  MODIFY `Variant_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- add variant form
--
INSERT INTO `core_form` (`form_key`, `label`, `entity_class`, `entity_tbl_class`) VALUES
('articlevariant-single', 'Article Variant', 'OnePlace\\Article\\Variant\\Model\\Variant', 'OnePlace\\Article\\Variant\\Model\\VariantTable');

--
-- add form tab
--
INSERT INTO `core_form_tab` (`Tab_ID`, `form`, `title`, `subtitle`, `icon`, `counter`, `sort_id`, `filter_check`, `filter_value`) VALUES
('variant-base', 'articlevariant-single', 'Variant', 'Executions', 'fas fa-base', '', '1', '', '');

--
-- add address fields
--
INSERT INTO `core_form_field` (`Field_ID`, `type`, `label`, `fieldkey`, `tab`, `form`, `class`, `url_view`, `url_list`, `show_widget_left`, `allow_clear`, `readonly`, `tbl_cached_name`, `tbl_class`, `tbl_permission`) VALUES
(NULL, 'text', 'Label', 'label', 'variant-base', 'articlevariant-single', 'col-md-3', '', '', '0', '1', '0', '', '', ''),
(NULL, 'hidden', 'Article', 'article_idfs', 'variant-base', 'articlevariant-single', 'col-md-3', '', '/', '0', '1', '0', '', '', '');

--
-- permission add variant
--
INSERT INTO `permission` (`permission_key`, `module`, `label`, `nav_label`, `nav_href`, `show_in_menu`) VALUES
('add', 'OnePlace\\Article\\Variant\\Controller\\VariantController', 'Add Variant', '', '', '0');