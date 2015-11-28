<?php

namespace App\Forms\Note;

use App\Model\NoteManager;
use App\Model\PadManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;


class NewNoteFormFactory extends Nette\Object
{

	/** @var PadManager */
	private $padManager;

	/**
	 * @var NoteManager
	 */
	private $noteManager;

	/**
	 * EditPadFormFactory constructor.
	 * @param PadManager  $padManager
	 * @param NoteManager $noteManager
	 */
	public function __construct(PadManager $padManager, NoteManager $noteManager)
	{
		$this->padManager = $padManager;
		$this->noteManager = $noteManager;
	}

	/**
	 * @param int $pad
	 * @return Form
	 */
	public function create($pad)
	{
		$form = new Form;
		$form->addText('name', 'Name')
			->setRequired('%label is required');

		$form->addTextArea('text', 'Text')
			->setRequired('%label is required');

		$form->addSelect('pad', 'Pad', $this->padManager->findAll()->fetchPairs('id', 'name'))
			->setPrompt('Select %label')
			->setDefaultValue($pad);

		$form->addSubmit('submit', 'Save');

		$form->onSuccess[] = [$this, 'formSucceeded'];
		return $form;
	}

	/**
	 * @param Form      $form
	 * @param ArrayHash $values
	 */
	public function formSucceeded(Form $form, $values)
	{
		if (!$this->noteManager->add($values->name, $values->text, $values->pad)) {
			$form->addError("Failed to create pad");
		}
	}

}
