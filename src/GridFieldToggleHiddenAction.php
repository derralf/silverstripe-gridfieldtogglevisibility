<?php

namespace Derralf\GridFieldToggleVisibility;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridField_ColumnProvider;
use SilverStripe\Forms\GridField\GridField_ActionProvider;
use SilverStripe\Forms\GridField\GridField_FormAction;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\ValidationException;
use SilverStripe\View\Requirements;

class GridFieldToggleHiddenAction implements GridField_ColumnProvider, GridField_ActionProvider
{

    /**
     * The database field which specifies the visibility, defaults to "Hidden".
     *
     * @see setSortField()
     * @var string
     */
    protected $visibilityField;

    /**
     * @param string $visibilityField
     */
    public function __construct($visibilityField = 'Hidden')
    {
        //parent::__construct();
        $this->visibilityField = $visibilityField;
    }

    /**
     * @return string
     */
    public function getVisibilityField()
    {
        return $this->visibilityField;
    }

    /**
     * Sets the field used to specify the sort.
     *
     * @param string $sortField
     * @return GridFieldOrderableRows $this
     */
    public function setVisibilityField($field)
    {
        $this->visibilityField = $field;
        return $this;
    }


    public function augmentColumns($gridField, &$columns)
    {
        if(!in_array('ToggleVisibility', $columns)) {
            // $columns[] = 'ToggleVisibility';
            if(in_array('Reorder', $columns)) {
                array_splice($columns,1,0,'ToggleVisibility');
            } else {
                array_unshift($columns, 'ToggleVisibility');
            }
        }
    }

    public function getColumnAttributes($gridField, $record, $columnName)
    {
        GridFieldToggleVisibility::include_requirements();
        return ['class' => 'grid-field__col-compact'];
    }

    public function getColumnMetadata($gridField, $columnName)
    {
        if($columnName == 'ToggleVisibility') {
            return ['title' => ''];
        }
    }

    public function getColumnsHandled($gridField)
    {
        return ['ToggleVisibility'];
    }

    public function getColumnContent($gridField, $record, $columnName)
    {
        if (!$record->canEdit()) return;

        $isHidden = $record->getField($this->getVisibilityField());

        //if ($record->Hidden) {
        if ($isHidden) {
            $extraClass = 'btn btn--no-text btn--icon-md font-icon-eye-with-line grid-field__icon-action gridfield-button-toggle-visibility record-not-visible';
        } else if ($record->hasMethod('getCMSWarning') && $record->getCMSWarning()) {
            $extraClass = 'btn btn--no-text btn--icon-md font-icon-attention grid-field__icon-action gridfield-button-toggle-visibility record-has-warning';
        } else {
            $extraClass = 'btn btn--no-text btn--icon-md font-icon-eye grid-field__icon-action gridfield-button-toggle-visibility record-is-visible';
        }


        $title = _t(__CLASS__ . '.ToggleHidden', "Toggle Visibility");

        $field = GridField_FormAction::create(
            $gridField,
            'ToggleHIddenAction'.$record->ID,
            false, // 'Do Action',
            "dotogglehiddenaction",
            ['RecordID' => $record->ID]
        )
            ->addExtraClass($extraClass)
            ->setAttribute('title', $title)
            ->setAttribute('aria-label', $title);



        return $field->Field();
    }

    public function getActions($gridField)
    {
        return ['dotogglehiddenaction'];
    }

    public function handleAction(GridField $gridField, $actionName, $arguments, $data)
    {
        if($actionName == 'dotogglehiddenaction') {
            // perform your action here
            $item = $gridField->getList()->byID($arguments['RecordID']);
            if (!$item) {
                return;
            }
            if (!$item->canEdit()) {
                throw new ValidationException(
                    _t(__CLASS__ . '.EditPermissionsFailure', "No permission to unlink record")
                );
            }
            //if ($item->getField('Hidden')) {
            //    $item->setField('Hidden', 0);
            //} else {
            //    $item->setField('Hidden', 1);
            //}
            if ($item->getField($this->getVisibilityField())) {
                $item->setField($this->getVisibilityField(), 0);
            } else {
                $item->setField($this->getVisibilityField(), 1);
            }
            $item->write();


            // output a success message to the user
            Controller::curr()->getResponse()->setStatusCode(
                200,
                'Visibility toggled.'
            );
        }
    }


}
