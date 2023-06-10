import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { UserParams } from '../_models/userParams';
import { AccountService } from '../_services/account.service';
import { MembersService } from '../_services/members.service';
import { MessageService } from '../_services/message.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit{
  constructor(private messageService: MessageService, private accountService: AccountService, private memberService:MembersService, private toastr: ToastrService, private fb: FormBuilder, private router: Router) { }
  model: any = {};
  loginForm: FormGroup = new FormGroup({});

  ngOnInit(){
    this.initializeForm();
  }

  initializeForm(){
    this.loginForm = this.fb.group({
      username: ['', Validators.required],
      password: ['', [Validators.required, Validators.minLength(6), Validators.maxLength(16)]],
    });
  }
  
  login() {
    console.log(this.model);
    
    this.accountService.login(this.loginForm.value).subscribe({
      next: response => {
        this.messageService.getMessagesThreadsInfo();
        this.memberService.createUserParams();
        this.router.navigateByUrl('/cards');
      }
    }
    )
  } 
}
