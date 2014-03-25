<?php namespace Ccdaparsers;
////////////////////////////////////////////////////////////////////////////////
// 
// php-ccda
// John Schrom
// http://john.mn
// 
// Objective: Parse a CCDA XML string into discrete health data elements.
//

////////////////////////////////////////////////////////////////////////////////


class Ccdaparser {

	function __construct($string = '') {

		// Initialize variables
		$this->xml = false;
		$this->rx = array();
		$this->dx = array();
		$this->lab = array();
		$this->immunization = array();
		$this->proc = array();
		$this->vital = array();
		$this->allergy = array();
		$this->enc = array();
		$this->plan = array();

		// If data was passed with constructor, parse it.
		if ($string != '') {
			if(!is_object($string)) {
				$string = simplexml_load_string($string);
			}
			$this->load_xml($string);
		}
	}
	
	function construct_json() {
		$patient = $this->demo;
		$patient->provider = $this->provider;
		$patient->rx = $this->rx;
		$patient->dx = $this->dx;
		$patient->lab = $this->lab;
		$patient->immunizaiton = $this->immunization;
		$patient->proc = $this->proc;
		$patient->vital = $this->vital;
		$patient->allergy = $this->allergy;
		$patient->enc = $this->enc;
		$patient->care_plan = $this->plan;
		return json_encode($patient, JSON_PRETTY_PRINT);
	}
	
	function load_xml($xmlObject) {
		$this->xml = $xmlObject;
		$this->parse();
		return true;
	}
	
	private function get_attr($xml, $attr) {
		if (is_object($xml)) {
		return (string) $xml->attributes()->{$attr};
		}
		else {
		return '';
		}
	}
	
	private function parse() {
		// Parse demographics
		$this->parse_demo($this->xml->recordTarget->patientRole);
		
		// Parse components
		$xmlRoot = $this->xml->component->structuredBody;
		$i = 0;
		while(is_object($xmlRoot->component[$i])) {
			$test = $xmlRoot->component[$i]->section->templateId->attributes()->root;
			
			// Medications
			if ($test == '2.16.840.1.113883.10.20.22.2.1.1'){
				$this->parse_meds($xmlRoot->component[$i]->section);
			}
			// Allergies
			else if ($test == '2.16.840.1.113883.10.20.22.2.6.1') {
				$this->parse_allergies($xmlRoot->component[$i]->section);
			}
			// Encounters
			else if ($test == '2.16.840.1.113883.10.20.22.2.22' or
						 $test == '2.16.840.1.113883.10.20.22.2.22.1') {
				$this->parse_enc($xmlRoot->component[$i]->section);
			}
			// Immunizations
			else if ($test == '2.16.840.1.113883.10.20.22.2.2.1' or 
					 $test == '2.16.840.1.113883.10.20.22.2.2') {
				$this->parse_immunizations($xmlRoot->component[$i]->section);
			}
			// Labs
			else if ($test == '2.16.840.1.113883.10.20.22.2.3.1') {
				$this->parse_labs($xmlRoot->component[$i]->section);
			}

			// Problems
			else if ($test == '2.16.840.1.113883.10.20.22.2.5.1' or 
					 $test == '2.16.840.1.113883.10.20.22.2.5') {
				$this->parse_dx($xmlRoot->component[$i]->section);
			}
			// Procedures
			else if ($test == '2.16.840.1.113883.10.20.22.2.7.1' or 
					 $test == '2.16.840.1.113883.10.20.22.2.7') {
				$this->parse_proc($xmlRoot->component[$i]->section);
			}
			// Vitals
			if ($test == '2.16.840.1.113883.10.20.22.2.4.1') {
				$this->parse_vitals($xmlRoot->component[$i]->section);
			}
			// Care Plan
			if ($test == '2.16.840.1.113883.10.20.22.2.10') {
				$this->parse_careplan($xmlRoot->component[$i]->section);
			}
			$i++;
		}
		return true;
	}
	
	private function parse_meds($xmlMed) {
		foreach($xmlMed->entry as $entry) {
			$n = count($this->rx);
			$this->rx[$n]->date_range->start = $this->get_attr($entry->substanceAdministration->effectiveTime->low,'value');
			$this->rx[$n]->date_range->end = $this->get_attr($entry->substanceAdministration->effectiveTime->high,'value');
			$this->rx[$n]->product_name = $this->get_attr($entry->substanceAdministration->consumable->manufacturedProduct->manufacturedMaterial->code,'displayName');
			$this->rx[$n]->product_code = $this->get_attr($entry->substanceAdministration->consumable->manufacturedProduct->manufacturedMaterial->code,'code');
			$this->rx[$n]->product_code_system = $this->get_attr( $entry ->substanceAdministration->consumable->manufacturedProduct->manufacturedMaterial->code,'codeSystem');
			$this->rx[$n]->translation->name = $this->get_attr($entry->substanceAdministration->consumable->manufacturedProduct->manufacturedMaterial->code->translation,'displayName');
			$this->rx[$n]->translation->code_system = $this->get_attr($entry->substanceAdministration->consumable->manufacturedProduct->manufacturedMaterial->code->translation,'codeSystemName');
			$this->rx[$n]->translation->code = $this->get_attr($entry->substanceAdministration->consumable->manufacturedProduct->manufacturedMaterial->code->translation,'code');
			$this->rx[$n]->dose_quantity->value = $this->get_attr($entry->substanceAdministration->doseQuantity,'value');
			$this->rx[$n]->dose_quantity->unit = $this->get_attr($entry->substanceAdministration->doseQuantity,'unit');
		}
		return true;
	}
	
	private function parse_demo($xmlDemo) {
		// Extract Demographics
		$this->demo->addr->street = array((string)$xmlDemo->addr->streetAddressLine[0],(string)$xmlDemo->addr->streetAddressLine[1]);
		$this->demo->addr->city = (string) $xmlDemo->addr->city;
		$this->demo->addr->state = (string) $xmlDemo->addr->state;
		$this->demo->addr->postalCode = (string) $xmlDemo->addr->postalCode;
		$this->demo->addr->country = (string) $xmlDemo->addr->country;
		$this->demo->phone->number = $this->get_attr($xmlDemo->telecom,'value');
		$this->demo->phone->use = $this->get_attr($xmlDemo->telecom,'use');
		$this->demo->name->first = (string) $xmlDemo->patient->name->given;
		$this->demo->name->last = (string) $xmlDemo->patient->name->family;
		$this->demo->gender = $this->get_attr($xmlDemo->patient->administrativeGenderCode,'code');
		$this->demo->birthdate = $this->get_attr($xmlDemo->patient->birthTime,'value');
		$this->demo->maritalStatus = $this->get_attr($xmlDemo->patient->maritalStatusCode, 'displayName');
		$this->demo->race = $this->get_attr($xmlDemo->patient->raceCode,'displayName');
		$this->demo->ethnicity = $this->get_attr($xmlDemo->patient->ethnicGroupCode,'displayName');
		$this->demo->language = $this->get_attr($xmlDemo->patient->languageCommunication->languageCode,'code');
		
		// Extract provider info
		$this->provider->organization->name = (string) $xmlDemo  ->providerOrganization->name;
		$this->provider->organization->phone = $this->get_attr($xmlDemo  ->providerOrganization->telecom,'value');
		$this->provider->organization->addr->street = array((string) $xmlDemo ->providerOrganization->addr->streetAddressLine[0],(string) $xmlDemo ->providerOrganization->addr->streetAddressLine[1]);
		$this->provider->organization->addr->city = (string) $xmlDemo  ->providerOrganization->addr->city;
		$this->provider->organization->addr->state = (string) $xmlDemo  ->providerOrganization->addr->state;
		$this->provider->organization->addr->postalCode = (string) $xmlDemo  ->providerOrganization->addr->postalCode;
		$this->provider->organization->addr->country = (string) $xmlDemo  ->providerOrganization->addr->country;
		return true;
	}
	
	private function parse_allergies($xmlAllergy) {
		foreach($xmlAllergy->entry as $entry) {
			$n = count($this->allergy);
			$this->allergy[$n]->date_range->start = $this->get_attr( $entry->act->effectiveTime->low,'value');
			$this->allergy[$n]->date_range->end = $this->get_attr($entry->act->effectiveTime->high,'value');
			$this->allergy[$n]->name=$this->get_attr($entry->act->entryRelationship->observation->code,'displayName');
			$this->allergy[$n]->code=$this->get_attr($entry->act->entryRelationship->observation->code,'code');
			$this->allergy[$n]->code_system = $this->get_attr($entry->act->entryRelationship->observation->code,'codeSystem');
			$this->allergy[$n]->code_system_name = $this->get_attr($entry->act->entryRelationship->observation->code,'codeSystemName');
			$this->allergy[$n]->allergen->name = $this->get_attr($entry->act->entryRelationship->observation->participant->participantRole->playingEntity->code,'displayName');
			$this->allergy[$n]->allergen->code = $this->get_attr($entry->act->entryRelationship->observation->participant->participantRole->playingEntity->code,'code');
			$this->allergy[$n]->allergen->code_system = $this->get_attr( $entry->act->entryRelationship->observation->participant->participantRole->playingEntity->code,'codeSystem');
			$this->allergy[$n]->allergen->code_system_name = $this->get_attr($entry->act->entryRelationship->observation->participant->participantRole->playingEntity->code,'codeSystemName');
			$this->allergy[$n]->reaction_type->name = $this->get_attr($entry ->act->entryRelationship->observation->value,'displayName');
			$this->allergy[$n]->reaction_type->code = $this->get_attr($entry ->act->entryRelationship->observation->value,'code');
			$this->allergy[$n]->reaction_type->code_system = $this->get_attr($entry ->act->entryRelationship->observation->value,'codeSystem');
			$this->allergy[$n]->reaction_type->code_system_name = $this->get_attr($entry ->act->entryRelationship->observation->value,'codeSystemName');
			$entryRoot = $entry->act->entryRelationship->observation;
			foreach($entryRoot->entryRelationship as $detail) {
				if (!is_object($detail->observation->templateId)) continue;
				$test = $detail->observation->templateId->attributes()->root;
				$varname = '';
				if ($test == '2.16.840.1.113883.10.20.22.4.9') {
					$varname = 'reaction';
					}
				if ($test == '2.16.840.1.113883.10.20.22.4.8') {
					$varname = 'severity';
					}
				if ($varname != '') {
					$this->allergy[$n]->{$varname}->name = $this->get_attr($detail->observation->value,'displayName');
					$this->allergy[$n]->{$varname}->code = $this->get_attr($detail->observation->value,'code');
					$this->allergy[$n]->{$varname}->code_system = $this->get_attr($detail->observation->value,'codeSystem');
					$this->allergy[$n]->{$varname}->code_system_name = $this->get_attr($detail->observation->value,'codeSystemName');
				}
			}
		}
		return true;
	}

	private function parse_immunizations($xmlImm) {
		foreach($xmlImm->entry as $entry) {
			$n = count($this->immunization);
			$entryRoot = $entry->substanceAdministration;
			$this->immunization[$n]->date = $this->get_attr($entryRoot->effectiveTime, 'value');
			$this->immunization[$n]->product->name = $this->get_attr($entryRoot->consumable->manufacturedProduct->manufacturedMaterial->code,'displayName');
			$this->immunization[$n]->product->code = $this->get_attr( $entryRoot->consumable->manufacturedProduct->manufacturedMaterial->code,'code');
			$this->immunization[$n]->product->code_system = $this->get_attr($entryRoot->consumable->manufacturedProduct->manufacturedMaterial->code,'codeSystem');
			$this->immunization[$n]->product->code_system_name = $this->get_attr($entryRoot->consumable->manufacturedProduct->manufacturedMaterial->code,'codeSystemName');
			$this->immunization[$n]->product->translation->name = $this->get_attr($entryRoot->consumable->manufacturedProduct->manufacturedMaterial->code->translation,'displayName');
			$this->immunization[$n]->product->translation->code = $this->get_attr($entryRoot->consumable->manufacturedProduct->manufacturedMaterial->code->translation,'code');
			$this->immunization[$n] ->product ->translation ->code_system = $this->get_attr($entryRoot->consumable->manufacturedProduct->manufacturedMaterial->code->translation,'codeSystem');
			$this->immunization[$n]->product->translation->code_system_name = $this->get_attr($entryRoot->consumable->manufacturedProduct->manufacturedMaterial->code->translation,'codeSystemName');
			$this->immunization[$n] ->route ->name = $this->get_attr($entryRoot->routeCode,'displayName');
			$this->immunization[$n]->route->code = $this->get_attr($entryRoot->routeCode,'code');
			$this->immunization[$n]->route->code_system = $this->get_attr($entryRoot->routeCode,'codeSystem');
			$this->immunization[$n]->route ->code_system_name = $this->get_attr($entryRoot->routeCode,'codeSystemName');
		}
		return true;
	}

	private function parse_labs($xmlLab) {
		foreach($xmlLab->entry as $entry) {
			$n = count($this->lab);
			$this->lab[$n]->panel_name  = $this->get_attr($entry->organizer->code,'displayName');
			$this->lab[$n]->panel_code  = $this->get_attr($entry->organizer->code,'code');
			$this->lab[$n]->panel_code_system = $this->get_attr($entry->organizer->code,'codeSystem');
			$this->lab[$n]->panel_code_system_name = $this->get_attr($entry->organizer->code,'codeSystemName');
			$this->lab[$n]->results->date = $this->get_attr($entry->organizer->component->observation->effectiveTime,'value');
			$this->lab[$n]->results->name = $this->get_attr($entry->organizer->component->observation->code,'displayName');
			$this->lab[$n]->results->code = $this->get_attr($entry->organizer->component->observation->code,'code');
			$this->lab[$n]->results->code_system = $this->get_attr($entry->organizer->component->observation->code,'codeSystem');
			$this->lab[$n]->results->code_system_name = $this->get_attr($entry->organizer->component->observation->code,'codeSystemName');
			$this->lab[$n]->results->value = $this->get_attr($entry->organizer->component->observation->value,'value');
			$this->lab[$n]->results->unit = $this->get_attr($entry->organizer->component->observation->value,'unit');
			$this->lab[$n]->results->code = $this->get_attr($entry->organizer->component->observation->code,'code');}
		return true;
	}

	private function parse_dx($xmlDx) {
		foreach($xmlDx->entry as $entry) {
			$n = count($this->dx);
			
			$this->dx[$n]->date_range->start = $this->get_attr($entry->act->effectiveTime->low,'value');
			$this->dx[$n]->date_range->end = $this->get_attr( $entry->act->effectiveTime->high,'value');
			$this->dx[$n]->name = $this->get_attr($entry->act->entryRelationship->observation->value,'displayName');
			$this->dx[$n]->code = $this->get_attr($entry->act->entryRelationship->observation->value,'code');
			$this->dx[$n]->code_system = $this->get_attr($entry->act->entryRelationship->observation->value,'codeSystem');
			$this->dx[$n]->translation->name = $this->get_attr($entry->act->entryRelationship->observation->value->translation,'displayName');
			$this->dx[$n]->translation->code = $this->get_attr($entry->act->entryRelationship->observation->value->translation,'code');
			$this->dx[$n]->translation->code_system = $this->get_attr($entry->act->entryRelationship->observation->value->translation,'codeSystem');
			$this->dx[$n]->translation->code_system_name = $this->get_attr($entry->act->entryRelationship->observation->value->translation,'codeSystemName');
			$this->dx[$n]->status = $this->get_attr( $entry->act->entryRelationship->observation->entryRelationship->observation->value,'displayName');
		}
		return true;
	}

	private function parse_proc($xmlProc) {
		foreach($xmlProc->entry as $entry) {
			$n = count($this->proc);
			$this->proc[$n]->date = $this->get_attr($entry->procedure->effectiveTime,'value');
			$this->proc[$n]->name = $this->get_attr($entry->procedure->code,'displayName');
			$this->proc[$n]->code = $this->get_attr($entry->procedure->code,'code');
			$this->proc[$n]->code_system = $this->get_attr($entry->procedure->code,'codeSystem');
			$this->proc[$n]->performer->organization= (string) $entry->procedure->performer->assignedEntity->addr->name;
			$this->proc[$n]->performer->street= array((string) $entry->procedure->performer->assignedEntity->addr->streetAddressLine[0],(string) $entry ->procedure->performer->assignedEntity->addr->streetAddressLine[1]);
			$this->proc[$n]->performer->city = (string) $entry->procedure->performer->assignedEntity->addr->city;
			$this->proc[$n]->performer->state = (string) $entry->procedure->performer->assignedEntity->addr->state;
			$this->proc[$n]->performer->zip = (string) $entry->procedure->performer->assignedEntity->addr->postalCode;
			$this->proc[$n]->performer->country = (string) $entry->procedure->performer->assignedEntity->addr->country;
		if (is_object($entry->procedure->performer->assignedEntity->telecom)) {
			$this->proc[$n]->performer->phone = $this->get_attr($entry->procedure->performer->assignedEntity->telecom,'value');}
		}
		return true;
	}

	private function parse_vitals($xmlVitals) {
		foreach($xmlVitals->entry as $entry) {
			$n = count($this->vital);
			$this->vital[$n]->date = $this->get_attr($entry->organizer->effectiveTime,'value');
			// Pull each vital sign for a given date
			$this->vital[$n]->results = array();
			$m = 0;
			foreach($entry->organizer->component as $component) {
				$this->vital[$n]->results[$m]->name = $this->get_attr($component->observation->code,'displayName');
				$this->vital[$n]->results[$m]->code = $this->get_attr($component->observation->code,'code');
				$this->vital[$n]->results[$m]->code_system = $this->get_attr($component->observation->code,'codeSystem');
				$this->vital[$n]->results[$m]->code_system_name = $this->get_attr($component->observation->code,'codeSystemName');
				$this->vital[$n]->results[$m]->value = $this->get_attr($component->observation->value,'value');
				$this->vital[$n]->results[$m]->unit = $this->get_attr($component->observation->value,'unit');
				$m++;
			}
		}
		return true;
	}
	private function parse_enc($xmlEnc) {
		foreach($xmlEnc->entry as $entry) {
			$n = count($this->enc);
			$this->enc[$n]->date = $this->get_attr($entry->encounter->effectiveTime,'value');
			$this->enc[$n]->name  = $this->get_attr($entry->encounter->code,'displayName');
			$this->enc[$n]->code= $this->get_attr( $entry->encounter->code,'code');
			$this->enc[$n]->code_system = $this->get_attr($entry->encounter->code,'codeSystem');
			$this->enc[$n]->code_system_name = $this->get_attr($entry->encounter->code,'codeSystemName');
			$this->enc[$n]->code_system_version = $this->get_attr($entry->encounter->code,'codeSystemVersion');
			$this->enc[$n]->finding->name = $this->get_attr($entry->encounter->entryRelationship->observation->value,'displayName');
			$this->enc[$n]->finding->code = $this->get_attr($entry->encounter->entryRelationship->observation->value,'code');
			$this->enc[$n]->finding->code_system = $this->get_attr($entry->encounter->entryRelationship->observation->value,'codeSystem');
			$this->enc[$n]->performer->name = $this->get_attr($entry->encounter ->performer->assignedEntity->code,'displayName');
			$this->enc[$n]->performer->code_system = $this->get_attr($entry->encounter->performer->assignedEntity->code,'codeSystem');
			$this->enc[$n]->performer->code = $this->get_attr( $entry->encounter->performer->assignedEntity->code,'code');
			$this->enc[$n]->performer->code_system_name = $this->get_attr($entry->encounter->performer->assignedEntity->code,'codeSystemName');
			$this->enc[$n]->location->organization = $this->get_attr($entry->encounter->participant->participantRole->code,'displayName');
			$this->enc[$n]->location->street = array((string) $entry->encounter->participant->participantRole->addr->streetAddressLine[0],(string) $entry->encounter->participant->participantRole->addr->streetAddressLine[1]);
			$this->enc[$n]->location->city = (string) $entry->encounter->participant->participantRole->addr->city;
			$this->enc[$n]->location->state = (string) $entry->encounter->participant->participantRole->addr->state;
			$this->enc[$n]->location->zip = (string) $entry->encounter->participant->participantRole->addr->postalCode;
			$this->enc[$n]->location->country = (string) $entry->encounter->participant->participantRole->addr->country;
		}
		return true;
	}
	
	private function parse_careplan($xmlCare) {
		foreach($xmlCare->entry as $entry) {
			$n = count($this->plan);
			if (is_object($entry->act->code)) $entryRoot = $entry->act;
			elseif (is_object($entry->observation->code)) $entryRoot = $entry->observation;
			else continue;
			$this->plan[$n]->name = $this->get_attr($entryRoot->code,'displayName');
			$this->plan[$n]->code = $this->get_attr($entryRoot->code,'code');
			$this->plan[$n]->code_system = $this->get_attr($entryRoot->code,'codeSystem');
			$this->plan[$n]->text = (string)  $entryRoot->text;
			$this->plan[$n]->status = $this->get_attr($entryRoot->statusCode,'code');
		}
		return true;
	}
}
