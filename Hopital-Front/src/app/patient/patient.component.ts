import { Component, Input, OnInit } from '@angular/core';
import { FormArray, FormBuilder, FormGroup } from '@angular/forms';
import { PatientService } from '../service/patient.service';
import { Consultation, Data, Model, NewResponse, Patient } from '../interface/liste';
import { environment } from 'src/environments/environment';


@Component({
  selector: 'app-patient',
  templateUrl: './patient.component.html',
  styleUrls: ['./patient.component.css']
})
export class PatientComponent implements OnInit {

  patients: Patient[] = [];

  consultations: Consultation[] = []

  display: string ="patient"

  constructor(private servicePatient: PatientService) { }
  ngOnInit(): void {
    this.listePatients()
    this.listeConsultations()
  }

  listePatients() {
    this.servicePatient.url = environment.apiURL + '/receptionniste/liste/patient';
    this.servicePatient.all().subscribe((response: Model<Data>) => {
      console.log(response);
      this.patients = response.data as unknown as Patient[];
      console.log(this.patients[0].prenom)
    });
  }

  listeConsultations(){
    this.servicePatient.url = environment.apiURL + '/receptionniste/liste/consultation';
    this.servicePatient.all().subscribe((response:any)=>{
      console.log(response);
      this.consultations = response.data as unknown as Consultation[];
      
    })
  }

  createPatient(data:Model<Data>){
    this.servicePatient.url = environment.apiURL + '/ajout/patient';
    this.servicePatient.store(data).subscribe((response: Model<Data>)=>{
      console.log(data);
      
    })

  }

  handleData(formData: any) {
    console.log(formData);
  }
  getPatient(){
    this.display="patient"
  }
  getConsultation(){
    this.display="consultation"
  }
}
