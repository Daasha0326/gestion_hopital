import { Injectable } from '@angular/core';
import { AbstractService } from './abstract.service';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Data, Model, Patient } from '../interface/liste';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class PatientService extends AbstractService<Data>{
  // override url(): string {
  //   return 'patients';
  // }
   override url = environment.apiURL;

  constructor(http: HttpClient) {
    super(http);
  }
  allSelect() {
    return this.http.get(`http://127.0.0.1:8000/api/select`)
  }

  rechercherParNumeroDossier(numDossier: any) {
    return this.http.get(`http://127.0.0.1:8000/api/receptionniste/search/${numDossier}`);
  }
  
}
