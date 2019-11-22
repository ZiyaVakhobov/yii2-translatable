# Translatable
## Widget for Yii2 framework projects. To Json Translate.
`composer require ziya/yii2-translate"^0.1"`

##  Migrations
`Attribute that you want translatable should be json field`
<br/>
<br/>
```
  $this->createTable('{{%article}}', [
            'id' => $this->primaryKey(),
            'name' => $this->json(),
            ....
        ]);
```

## Active Records
Your model should use trait Translatable.
```
use Translatable; 
```
<br/>

If you want to make them required by language then use TranslatableValidator 
rule then provide what language is required

```
TranslatableValidator::class
```
<br/>
You have to use TranslatableBehaviour class and show which attribute should follow translatable

```
TranslatableBehaviour::class
```
<br/>
All code is below with examples
<br/>

```
class Article extends ActiveRecord
{
    use Translatable; 


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], TranslatableValidator::class, 'languages' => ['uz','ru']],
            [['description'],'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TranslatableBehaviour::class,
                'attributes' => ['name'']
            ],
        ];
    }
```

## View File
Your Form will look like this. Give languageList as array.
```
$languageList = ['eng','ru','fr'];

foreach ($languageList as $lang) {
        echo $form
            ->field($model, "name[{$lang}]")
            ->textInput(['value'=>$model->name->other($lang)])
            ->label($model->getAttributeLabel('name') . "_{$lang}");
}
```


# Forms
### If you are using Form instead of ActiveRecord. You need to set type to Model.
By default it is TYPE_ACTIVE_RECORD, so you need to set TYPE_MODEL. See below how it is done
```
class ArticleForm extends Model
{
    
    public $content;
    public function behaviors()
    {
        return [
            'translatable'=>[
                'class' => TranslatableBehaviour::className(),
                'attributes' => ['content'],
                'type' => TranslatableBehaviour::TYPE_MODEL,
            ],
        ];
    }
```