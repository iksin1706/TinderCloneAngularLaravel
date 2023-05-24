import { Injectable } from '@angular/core';
import { NgxSpinnerService } from 'ngx-spinner';

@Injectable({
  providedIn: 'root'
})
export class BusyService {
  busyRequestCount = 0;

  constructor(private spinnerService: NgxSpinnerService) { }

  busy(){
    this.busyRequestCount++;
    let progressBar = document.querySelector("#rainbow-progress-bar") as HTMLElement;
    if(progressBar)  progressBar.style.display = 'block';
  }

  idle(){
    this.busyRequestCount--;
    if(this.busyRequestCount <=0){
      this.busyRequestCount=0;
      let progressBar = document.querySelector("#rainbow-progress-bar") as HTMLElement;
      if(progressBar)  progressBar.style.display = 'none';
    }
  }
}
