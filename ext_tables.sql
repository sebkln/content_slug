#
# Modifying tt_content table
#
CREATE TABLE tt_content (
    tx_content_slug_fragment varchar(255) DEFAULT '' NOT NULL,
    tx_content_slug_link TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL
);
