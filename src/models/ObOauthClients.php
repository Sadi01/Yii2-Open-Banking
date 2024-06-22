<?php

namespace sadi01\openbanking\models;

use Yii;
use sadi01\openbanking\behaviors\Jsonable;

/**
 * This is the model class for table "{{%ob_oauth_clients}}".
 *
 * @property int $id
 * @property string $client_id
 * @property string $base_url
 * @property string|null $client_secret
 * @property string $grant_types
 * @property string|null $scope
 * @property string|null $username
 * @property string|null $password
 * @property string|null $add_on
 *
 * @mixin Jsonable;
 */
class ObOauthClients extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;

    const SCENARIO_DELETE = 'delete';
    const SCENARIO_FARABOOM = 'faraboom';
    const SCENARIO_FINNOTECH = 'finnotech';

    const PLATFORM_FARABOOM = 1;
    const PLATFORM_FINNOTECH = 2;


    public ?string $app_key = null;
    public ?int $finno_limit = null;
    public ?int $finno_count = null;
    public ?string $app_secret = null;
    public ?string $app_password = null;
    public ?string $nid = null;
    public ?string $client_device_id = null;
    public ?string $bank_id = null;
    public ?string $client_platform_type = null;
    public ?string $client_user_id = null;
    public ?string $device_id = null;
    public ?string $token_id = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ob_oauth_clients}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'base_url'], 'required'],
            [['app_key', 'app_password', 'nid'], 'required','on' => self::SCENARIO_FINNOTECH],
            [['client_id', 'base_url', 'app_key', 'app_secret', 'bank_id', 'client_device_id', 'client_platform_type', 'client_user_id', 'device_id', 'token_id'], 'required', 'on' => [self::SCENARIO_FARABOOM]],
            [['add_on'], 'safe'],
            [['finno_limit','finno_count'], 'integer'],
            [['client_id', 'client_secret'], 'string', 'max' => 32],
            [['base_url'], 'string', 'max' => 255],
            [['grant_types', 'username'], 'string', 'max' => 100],
            [['scope', 'password', 'app_secret'], 'string', 'max' => 2000],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DELETE] = ['!status'];
        $scenarios[self::SCENARIO_FARABOOM] = ['username', 'password', 'client_id', 'base_url', 'grant_types', 'app_key', 'app_secret', 'bank_id', 'client_device_id', 'client_platform_type', 'client_user_id', 'device_id', 'token_id'];
        $scenarios[self::SCENARIO_FINNOTECH] = ['app_key','app_password','nid','client_id', 'base_url','finno_limit','finno_count'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('openBanking', 'ID'),
            'client_id' => Yii::t('openBanking', 'Client ID'),
            'base_url' => Yii::t('openBanking', 'Base Url'),
            'client_secret' => Yii::t('openBanking', 'Client Secret'),
            'grant_types' => Yii::t('openBanking', 'Grant Types'),
            'scope' => Yii::t('openBanking', 'Scope'),
            'username' => Yii::t('openBanking', 'Username'),
            'password' => Yii::t('openBanking', 'Password'),
            'add_on' => Yii::t('openBanking', 'Add On'),
            'app_key' => Yii::t('openBanking', 'App Key'),
            'app_secret' => Yii::t('openBanking', 'App Secret'),
            'client_device_id' => Yii::t('openBanking', 'Client Device ID'),
            'bank_id' => Yii::t('openBanking', 'Bank ID'),
            'client_platform_type' => Yii::t('openBanking', 'Client Platform Type'),
            'client_user_id' => Yii::t('openBanking', 'Client User ID'),
            'device_id' => Yii::t('openBanking', 'Device ID'),
            'token_id' => Yii::t('openBanking', 'Token ID'),
            'finno_limit' => Yii::t('openBanking', 'Finno Limit'),
            'finno_count' => Yii::t('openBanking', 'Finno Count'),
            'app_password' => Yii::t('openBanking', 'App Password'),
            'nid' => Yii::t('openBanking', 'Nid'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ObOauthClientsQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new ObOauthClientsQuery(get_called_class());
        return $query;
    }

    public static function itemAlias($type, $code = NULL)
    {
        $_items = [
            'Status' => [
                self::STATUS_ACTIVE => Yii::t('openBanking', 'Active'),
                self::STATUS_DELETED => Yii::t('openBanking', 'Deleted')
            ],
            'StatusClass' => [
                self::STATUS_ACTIVE => 'success',
                self::STATUS_DELETED => 'danger'
            ],
            'StatusColor' => [
                self::STATUS_ACTIVE => '#23a665',
                self::STATUS_DELETED => '#ff5050',
            ],
            'Client' => [
                self::PLATFORM_FARABOOM => Yii::t('openBanking', 'Faraboom'),
                self::PLATFORM_FINNOTECH => Yii::t('openBanking', 'Finnotech')
            ],
        ];

        if (isset($code))
            return $_items[$type][$code] ?? false;
        else
            return $_items[$type] ?? false;
    }


    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'Jsonable' => [
                'class' => Jsonable::class,
                'jsonAttributes' => [
                    'add_on' => [
                        'app_key',
                        'app_secret',
                        'client_device_id',
                        'bank_id',
                        'client_platform_type',
                        'client_user_id',
                        'device_id',
                        'token_id',
                        'app_password',
                        'nid',
                        'finno_limit',
                        'finno_count'
                    ],
                ],
            ],
        ]);
    }

    /**
     * @return string[]
     */
    public function fields(): array
    {
        $fields = [
            'id',
        ];

        return $fields;
    }

    /**
     * @return string[]
     */
    public function extraFields(): array
    {
        $extraFields = [

        ];

        return $extraFields;
    }
}