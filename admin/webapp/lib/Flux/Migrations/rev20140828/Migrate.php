<?php
namespace Flux\Migrations\rev20140828;

use Mojavi\Migration\Migration;
use Mojavi\Util\StringTools;

class Migrate extends Migration {
	
	/**
	 * Upgrades to this version
	 * @return boolean
	 */
	function up() {
		StringTools::consoleWrite('Updating data sets for datafields', 'Updating', StringTools::CONSOLE_COLOR_RED);
		$data_field = new \Flux\DataField();
		$data_field->setIgnorePagination(true);
		$data_fields = $data_field->queryAll();
		foreach ($data_fields as $data_field) {
			StringTools::consoleWrite('Updating data sets for datafields', $data_field->getKeyName(), StringTools::CONSOLE_COLOR_YELLOW);
			if ($data_field->getKeyName() == 'st') { // State
			    $data_field_sets = array();
			    $items = array("Alabama" => "AL", "Alaska" => "AK", "Arizona" => "AZ", "Arkansas" => "AR", "California" => "CA", "Colorado" => "CO", "Connecticut" => "CT", "Delaware" => "DE", "District of Columbia" => "DC", "Florida" => "FL", "Georgia" => "GA", "Hawaii" => "HI", "Idaho" => "ID", "Illinois" => "IL", "Indiana" => "IN", "Iowa" => "IA", "Kansas" => "KS", "Kentucky" => "KY", "Louisiana" => "LA", "Maine" => "ME", "Montana" => "MT", "Nebraska" => "NE", "Nevada" => "NV", "New Hampshire" => "NH", "New Jersey" => "NJ", "New Mexico" => "NM", "New York" => "NY", "North Carolina" => "NC", "North Dakota" => "ND", "Ohio" => "OH", "Oklahoma" => "OK", "Oregon" => "OR", "Maryland" => "MD", "Massachusetts" => "MA", "Michigan" => "MI", "Minnesota" => "MN", "Mississippi" => "MS", "Missouri" => "MO", "Pennsylvania" => "PA", "Rhode Island" => "RI", "South Carolina" => "SC", "South Dakota" => "SD", "Tennessee" => "TN", "Texas" => "TX", "Utah" => "UT", "Vermont" => "VT", "Virginia" => "VA", "Washington" => "WA", "West Virginia" => "WV", "Wisconsin" => "WI", "Wyoming" => "WY");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'ctry') { // which side
			    $data_field_sets = array();
			    $items = array("United States" => "US", "Canada" => "CA", "United Kingdom" => "UK");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'brand') { // Hip Brand
			    $data_field_sets = array();
			    // Defaults
			    $items = array("Other" => "other", "I don't know" => "not_sure");
			    // Hip Brands
			    $items = array_merge($items, array("DePuy" => "depuy", "Zimmer" => "zimmer", "Profemur" => "profemur", "Stryker" => "stryker", "Johnson & Johnson" => "johnson_johnson", "Biomet" => "biomet"));
			    // Mesh Brands
			    $items = array_merge($items, array("Avaulta Plus" => "avaulta_plus", "BioSynthetic Support" => "biosynthetic_support", "Gynecare TVT" => "gynecare_tvt", "Faslata Allograft" => "faslata_allograft", "Gynecare Prolift" => "gynecare_prolift", "Gynecare Prosima" => "gynecare_prosima", "Avaulta" => "avaulta", "Avaulta Solo" => "avaulta_solo", "Pelvicol Tissue" => "pelvicol_tissue", "Obtryx Curved Single" => "obtryx_curved_single", "Prefyx PPS System" => "prefyx_pps_system", "PelviSoft Biomesh Obtryx Mesh Sling" => "pelvisoft_biomesh_obtryx_mesh_sling", "Pelvitex Polypropylene" => "pelvitex_polypropylene", "Prefyx Mid U Mesh" => "prefyx_mid_u_mesh"));
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'effect_type') { // Hip Side Effect
			    $data_field_sets = array();
			    // Defaults
			    $items = array("Other" => "other", "I don't know" => "not_sure");
			    // Hip injuries
			    $items = array_merge($items, array("Hip Fracture" => "hip_fracture", "Hip Implant failure/dislocation" => "implant_failure", "Loosening of Implant" => "loosening_of_implant", "Received Recall Letter from Hip Manufacturer" => "hip_implant_letter_manufacturer", "Swelling, Extreme Pain, Discomfort" => "swelling_pain_discomfort", "Implant Complications" => "hip_implant_complications", "Received Recall letter from doctor or surgeon" => "letter_from_doctor_surgeon_manufacturer", "Failure or Dislocation of Implant" => "fracture_failure_dislocation_loosening", "Implant Infection (Pseudotumor/Alval)" => "infection_pseudotumer_alval", "Other Complications" => "complications", "Clicking, Popping, or Grinding" => "clicking_popping_grinding", "Revision/replacement surgery (needed/scheduled) " => "revision_surgery_scheduled", "Revision/replacement surgery (already done)" => "revision_surgery_done", "Infection Requiring Hospitalization" => "infection_require_hospital", "Metallosis (cobalt/chromium/metal blood poisoning)" => "metallosis"));
			    // Mesh injuries
			    $items = array_merge($items, array("Mesh Erosion" => "erosion_of_mesh_into_vagina", "Infection" => "infection", "Pain" => "vaginal_pain", "Pelvic Pain" => "pelvic_pain", "Recurrence of Prolapse" => "recurrence_sui_pop", "Continuation of worsening of incontinence" => "urinary_problems", "Bowel/Bladder/Blood Vessel Perforation" => "bowel_bladder_blood_vessel_perforation", "Vaginal Scarring" => "mesh_extrusion", "Need for corrective surgery" => "revision_surgery_needed", "Required corrective surgery" => "revision_surgery_required"));
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'lawyer') { // Lawyer Retained
			    $data_field_sets = array();
			    $items = array("YES" => "YES", "NO" => "NO");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'doc_said') { // Doctor recommended surgery
			    $data_field_sets = array();
			    $items = array("YES" => "YES", "NO" => "NO");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'side_effects') { // Experienced any side effects
			    $data_field_sets = array();
			    $items = array("YES" => "YES", "NO" => "NO");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'hip_treatment') { // Had a hip replacement
			    $data_field_sets = array();
			    $items = array("YES" => "YES", "NO" => "NO");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'mesh_treatment') { // Had a mesh replacement
			    $data_field_sets = array();
			    $items = array("YES" => "YES", "NO" => "NO");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'patch_sling') { // Had a mesh replacement
			    $data_field_sets = array();
			    $items = array("Patch" => "patch", "Sling" => "sling", "I don't know" => "not_sure");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'left_right') { // which side
			    $data_field_sets = array();
			    $items = array("LEFT" => "LEFT", "RIGHT" => "RIGHT", "BOTH" => "BOTH");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'month') { // month
			    $data_field_sets = array();
			    $items = array("January" => "1", "February" => "2", "March" => "3", "April" => "4", "May" => "5", "June" => "6", "July" => "7", "August" => "8", "September" => "9", "October" => "10", "November" => "11", "December" => "12");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'year') { // year
			    $data_field_sets = array();
			    for ($i=1990;$i<date("Y") + 10;$i++) {
			        $data_field_set = array('name' => $i, 'value' => $i);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'mesh_year') { // year
			    $data_field_sets = array();
			    for ($i=1990;$i<date("Y") + 10;$i++) {
			        $data_field_set = array('name' => $i, 'value' => $i);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'effect_time') { // effect time
			    $data_field_sets = array();
			    $items = array("Immediately" => "immediately", "Within the first month" => "1mo", "Within 3 months" => "3mo", "Within 6 months" => "6mo", "Within 1 year" => "1year", "Over 1 year later" => "1+");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'gender') { // which side
			    $data_field_sets = array();
			    $items = array("Male" => "male", "Female" => "female");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'gd') { // which side
			    $data_field_sets = array();
			    $items = array("Male" => "male", "Female" => "female");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'conv') { // conversion
			    $data_field_sets = array();
			    $items = array("Yes" => "1", "No" => "0");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'fulfilled') { // fulfilled
			    $data_field_sets = array();
			    $items = array("Yes" => "1", "No" => "0");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'pa') { // partial
			    $data_field_sets = array();
			    $items = array("Yes" => "1", "No" => "0");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'im') { // partial
			    $data_field_sets = array();
			    $items = array("Yes" => "1", "No" => "0");
			    foreach ($items as $name => $value) {
			        $data_field_set = array('name' => $name, 'value' => $value);
			        $data_field_sets[] = $data_field_set;
			    }
			    $data_field->setDataFieldSet($data_field_sets);
			    $data_field->update();
			}
		}
		StringTools::consoleWrite('Updating data sets for datafields', 'Updated', StringTools::CONSOLE_COLOR_GREEN, true);
	}
	
	/**
	 * Downgrades to this version
	 * @return boolean
	 */
	function down() {
	
	}
}
