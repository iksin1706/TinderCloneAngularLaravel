import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MessagesThreadsComponent } from './messages-threads.component';

describe('MessagesThreadsComponent', () => {
  let component: MessagesThreadsComponent;
  let fixture: ComponentFixture<MessagesThreadsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MessagesThreadsComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(MessagesThreadsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
