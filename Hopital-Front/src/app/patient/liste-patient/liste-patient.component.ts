import { Component, EventEmitter, OnInit, Input, Output, ViewChild, ElementRef } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { Medecin, Patient, Service } from 'src/app/interface/liste';
// import { Liste } from 'src/app/interface/liste';
import { PatientService } from 'src/app/service/patient.service';


@Component({
  selector: 'app-liste-patient',
  templateUrl: './liste-patient.component.html',
  styleUrls: ['./liste-patient.component.css']
})
export class ListePatientComponent implements OnInit {

  openModal: boolean = false;
  tableSizes: number[] = []
  tableSize: number = 5;
  count: number = 0
  page: number = 1
  monFormulaire!: FormGroup;
  medecin !: Medecin[]
  service !: Service[]
  numeroDossier!: string;
  numDossier!: string;
  rechercheEnCours: boolean = false;
  searchInput !: string;
  patient!: Patient;

  @Output() ajoutPatient = new EventEmitter<any>();
  @Output() patientTrouve: EventEmitter<Patient> = new EventEmitter<Patient>();
  @Output() erreurRecherche: EventEmitter<any> = new EventEmitter<any>();
  @Input() patients: Patient[] = []
  selectedPatient!: Patient;
  rechercheEffectuee: boolean = false;
  findPatient: boolean = false;
  constructor(private listeService: PatientService, private formBuilder: FormBuilder) {

  }

  ngOnInit() {
    this.monFormulaire = this.formBuilder.group({
      id: [],
      nom: [],
      prenom: [],
      telephone: [],
      adresse: [],
      age: [],
      sexe: [],
      etat: [],
      service_id: [],
      medecin_id: []
    })




    this.listeService.allSelect().subscribe((res: any) => {
      console.log(res);
      this.medecin = res.medecin_id,
        this.service = res.service_id

    })
  }


  openModalWithPatient(patient: Patient) {
    this.selectedPatient = patient;
    console.log(patient);
    this.monFormulaire.patchValue({
      nom: patient.nom,
      prenom: patient.prenom,
      telephone: patient.telephone,
      adresse: patient.adresse,
      age: patient.age,
      sexe: patient.sexe,

    });
    this.openModal = true;
  }


  recherche() {
    if (!this.numeroDossier) {
      this.rechercheEffectuee = false;
      this.findPatient = false;
      return;
    }
    this.patients = this.patients.filter(patient =>
      patient.numeroDossier.includes(this.numeroDossier)
    );
    this.rechercheEffectuee = true;
    this.findPatient = this.patients.length > 0;
  }


  closeModal() {
    this.openModal = false
  }
  addPatient() {
    this.openModal = true;
  }
  onTableChange(event: any) {
    this.tableSizes = event.target.value;
    this.page = 1;
  }

  saveData() {

    const formData = this.monFormulaire.value;
    console.log(formData);
    this.ajoutPatient.emit(formData);
  }

  // genererNumeroDossier() {
  //   let numDossier = this.inputNom.nativeElement.value.substring(0, 3) + this.inputPrenom.nativeElement.value.substring(0, 3) + this.inputTelephone.nativeElement.value.substring(0, 3);
  //   console.log(numDossier);
  //   this.numDossier = numDossier
  // }
}