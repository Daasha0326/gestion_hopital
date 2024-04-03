import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Data, Model, Patient } from '../interface/liste';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export abstract class AbstractService<T>{

  protected url: string = "";

  constructor(public http: HttpClient) { }

  // abstract uri():string;

  all(): Observable<Model<Data>> {
    return this.http.get<Model<Data>>(`${this.url}`);

  }
 
  store(data: any): Observable<Model<Data>> {
    return this.http.post<Model<Data>>(`${this.url}`, data);

  }

  show(id: string): Observable<Model<Data>> {
    return this.http.get<Model<Data>>(`${this.url}/${id}`);

  }

  update(data: any, id: string): Observable<Model<Data>> {
    return this.http.put<Model<Data>>(`${this.url}/${id}`, data);

  }

  supprimer(id: number): Observable<Model<Patient>> {
    return this.http.delete<Model<Patient>>(`${this.url}/${id}`);
  }

}