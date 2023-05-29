import { Component } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { AccountService } from '../_services/account.service';
import { MessageService } from '../_services/message.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {
  constructor(private messageService: MessageService, private accountService: AccountService, private toastr: ToastrService, private fb: FormBuilder, private router: Router) { }
  model: any = {};
  login() {
    console.log(this.model);
    
    this.accountService.login(this.model).subscribe({
      next: () => {
        this.router.navigateByUrl('/cards');
        this.messageService.getMessagesThreadsInfo();
      }
    }
    )
  } 
}
