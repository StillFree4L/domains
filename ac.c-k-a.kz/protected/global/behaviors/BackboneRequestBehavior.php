<?php

    namespace glob\behaviors;

    use glob\components\ActiveRecord;
    use yii\base\Behavior;

    class BackboneRequestBehavior extends Behavior
    {

        public function filterRulesForBackboneValidation()
        {

            $scenario = $this->owner->getScenario();

            $raw = $this->owner->rules();

            $backbone_rule_types = [
                "required",
                "compare",
                "date",
                "email",
                "in",
                "length",
                "numerical",
                "match",
                "type"
            ];

            $rules = [];
            if (!empty($raw)) {
                foreach ($raw as $r) {

                    if (isset($r['on']) AND $r['on'] != $scenario) continue;

                    if (!in_array($r['1'], $backbone_rule_types)) continue;

                    $rules[] = $r;

                }
            }

            return $rules;

        }

        public function requestAccess($type = "fetch", $attributes = [])
        {
            $m = $type."Access";
            return $this->owner->$m($attributes);
        }
        public function getAccess($attributes = [])
        {
            return false;
        }
        public function insertAccess($attributes = [])
        {
            return false;
        }
        public function updateAccess($attributes = [])
        {
            return false;
        }
        public function deleteAccess($attributes = [])
        {
            return false;
        }
        public function pollAccess($attributes = [])
        {
            return false;
        }

        public function backboneRequest($type, $attributes = [])
        {
            if ($this->owner->requestAccess($type, $attributes))
            {
                $m = $type."Request";
                return $this->owner->$m($attributes);
            } else {
                $this->owner->addError("access", \Yii::t("main","Нет доступа"));
                return false;
            }
        }
        public function getRequest($attributes = [])
        {
            return ActiveRecord::arrayAttributes($this->owner->findAllByAttributes($attributes));
        }

        public function insertRequest($attributes)
        {
            $this->owner->attributes = $attributes;
            if ($this->owner->save())
            {
                return ActiveRecord::arrayAttributes($this);
            } else {
                return false;
            }
        }

        public function updateRequest($attributes)
        {

        }

        public function pollRequest($attributes)
        {

        }


    }
?>