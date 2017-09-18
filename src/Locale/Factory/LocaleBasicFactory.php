<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Locale\Factory;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Factory\Factory;
use Shopware\Locale\Extension\LocaleExtension;
use Shopware\Locale\Struct\LocaleBasicStruct;
use Shopware\Search\QueryBuilder;
use Shopware\Search\QuerySelection;

class LocaleBasicFactory extends Factory
{
    const ROOT_NAME = 'locale';

    const FIELDS = [
       'uuid' => 'uuid',
       'code' => 'code',
       'language' => 'translation.language',
       'territory' => 'translation.territory',
    ];

    /**
     * @var LocaleExtension[]
     */
    protected $extensions = [];

    public function hydrate(
        array $data,
        LocaleBasicStruct $locale,
        QuerySelection $selection,
        TranslationContext $context
    ): LocaleBasicStruct {
        $locale->setUuid((string) $data[$selection->getField('uuid')]);
        $locale->setCode((string) $data[$selection->getField('code')]);
        $locale->setLanguage((string) $data[$selection->getField('language')]);
        $locale->setTerritory((string) $data[$selection->getField('territory')]);

        foreach ($this->extensions as $extension) {
            $extension->hydrate($locale, $data, $selection, $context);
        }

        return $locale;
    }

    public function getFields(): array
    {
        $fields = array_merge(self::FIELDS, parent::getFields());

        return $fields;
    }

    public function joinDependencies(QuerySelection $selection, QueryBuilder $query, TranslationContext $context): void
    {
        if ($translation = $selection->filter('translation')) {
            $query->leftJoin(
                $selection->getRootEscaped(),
                'locale_translation',
                $translation->getRootEscaped(),
                sprintf(
                    '%s.locale_uuid = %s.uuid AND %s.language_uuid = :languageUuid',
                    $translation->getRootEscaped(),
                    $selection->getRootEscaped(),
                    $translation->getRootEscaped()
                )
            );
            $query->setParameter('languageUuid', $context->getShopUuid());
        }

        $this->joinExtensionDependencies($selection, $query, $context);
    }

    public function getAllFields(): array
    {
        $fields = array_merge(self::FIELDS, $this->getExtensionFields());

        return $fields;
    }

    protected function getRootName(): string
    {
        return self::ROOT_NAME;
    }
}