<?php declare(strict_types=1);

namespace Shopware\Product\Gateway\Resource;

use Shopware\Framework\Write\Flag\Required;
use Shopware\Framework\Write\Field\FkField;
use Shopware\Framework\Write\Field\IntField;
use Shopware\Framework\Write\Field\ReferenceField;
use Shopware\Framework\Write\Field\StringField;
use Shopware\Framework\Write\Field\BoolField;
use Shopware\Framework\Write\Field\DateField;
use Shopware\Framework\Write\Field\SubresourceField;
use Shopware\Framework\Write\Field\LongTextField;
use Shopware\Framework\Write\Field\LongTextWithHtmlField;
use Shopware\Framework\Write\Field\FloatField;
use Shopware\Framework\Write\Field\TranslatedField;
use Shopware\Framework\Write\Field\UuidField;
use Shopware\Framework\Write\Resource;

class ProductConfiguratorSetGroupRelationResource extends Resource
{
    protected const SET_ID_FIELD = 'setId';
    protected const UUID_FIELD = 'uuid';
    protected const GROUP_ID_FIELD = 'groupId';

    public function __construct()
    {
        parent::__construct('product_configurator_set_group_relation');
        
        $this->primaryKeyFields[self::SET_ID_FIELD] = new IntField('set_id');
        $this->primaryKeyFields[self::UUID_FIELD] = (new UuidField('uuid'))->setFlags(new Required());
        $this->primaryKeyFields[self::GROUP_ID_FIELD] = new IntField('group_id');
    }
    
    public function getWriteOrder(): array
    {
        return [
            \Shopware\Product\Gateway\Resource\ProductConfiguratorSetGroupRelationResource::class
        ];
    }
}
