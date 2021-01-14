//
//  EventController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 11.01.2021.
//

import Foundation
import UIKit

class EventController: UIViewController {
   
    //будущие мероприятия
    var data = [["0","День кофе: пьем и не спим", "Собираемся, наливаем, выпиваем и уходим. Перед уходом платим, наличными конечно.", "7 января в 19:00", "До 60 человек","img_1"], ["1","День еды: собираемся, объедаемся и уходим", "Все как обычно, вы можете наесться за 10 минут – тогда ваш ужин обойдется вам в 10₽. Вам выгодно, а нам нет.", "7 января в 19:00", "До 60 человек","img_2"]]
    //прошедшие мероприятия
    var dataFinishedEvents = [["0","День кофе: пьем и не спим", "Собираемся, наливаем, выпиваем и уходим. Перед уходом платим, наличными конечно.", "21 января в 19:00", "До 60 человек","img_1"], ["1","День еды: собираемся, объедаемся и уходим", "Все как обычно, вы можете наесться за 10 минут – тогда ваш ужин обойдется вам в 10₽. Вам выгодно, а нам нет.", "15 января в 19:00", "До 60 человек","img_2"]]
    var dataNews = [["0", "Название новости 1", "Описание новости 1"],["2", "Название новости 2", "Описание новости 2"]]
    let idCell = "MailCell"
    @IBOutlet weak var sectionEvents: UIView!
    @IBOutlet weak var sectionNews: UIView!
    @IBOutlet weak var segmentControl: UISegmentedControl!
    @IBOutlet weak var titleFutureEvents: UIButton!
    @IBOutlet weak var titleFinishedEvents: UIButton!
    @IBAction func SelectFutureEvents(_ sender: Any) {
        tableEvents.isHidden = false
        tableFinishedEvents.isHidden = true
        titleFutureEvents.backgroundColor = UIColor(named: "Color-1")
        titleFutureEvents.setTitleColor(UIColor(named: "inputCode"), for: .normal)
        titleFinishedEvents.backgroundColor = UIColor(named: "Default")
        titleFinishedEvents.setTitleColor(UIColor(named: "events"), for: .normal)
    }
    @IBAction func SelectFinishedEvents(_ sender: Any) {
        tableEvents.isHidden = true
        tableFinishedEvents.isHidden = false
        titleFutureEvents.backgroundColor = UIColor(named: "Default")
        titleFutureEvents.setTitleColor(UIColor(named: "events"), for: .normal)
        titleFinishedEvents.backgroundColor = UIColor(named: "Color-1")
        titleFinishedEvents.setTitleColor(UIColor(named: "inputCode"), for: .normal)
    }
    //Количество мероприятий на которые записан пользователь
    @IBOutlet weak var countRegisterEvents: UILabel!
    @IBOutlet weak var tableEvents: UITableView!
    @IBOutlet weak var tableFinishedEvents: UITableView!
    @IBOutlet weak var tableNews: UITableView!
    override func viewDidLoad() {
        super.viewDidLoad()
        titleFutureEvents.setTitleColor(UIColor(named: "inputCode"), for: .normal)
        titleFinishedEvents.setTitleColor(UIColor(named: "events"), for: .normal)
        
        tableEvents.dataSource = self
        tableEvents.delegate = self
        
        tableFinishedEvents.dataSource = self
        tableFinishedEvents.delegate = self
        
        tableNews.dataSource = self
        tableNews.delegate = self
        
        self.navigationController?.navigationBar.setValue(true, forKey: "hidesShadow")
        self.navigationItem.backBarButtonItem = UIBarButtonItem(title: "", style: .plain, target: nil, action: nil)
        
        tableEvents.register(UINib(nibName: "MainTableViewCell", bundle: nil ), forCellReuseIdentifier: idCell)
        tableFinishedEvents.register(UINib(nibName: "MainTableViewCell", bundle: nil ), forCellReuseIdentifier: idCell)
        tableNews.register(UINib(nibName: "MainTableViewCell", bundle: nil ), forCellReuseIdentifier: idCell)
        
        segmentControl.addTarget(self, action: #selector(selectedValue), for: .valueChanged)
    }
    
    @objc func selectedValue(target: UISegmentedControl){
        if target == self.segmentControl {
            let segmentIndex = target.selectedSegmentIndex
            if (segmentIndex == 0){
                sectionEvents.isHidden = false
                sectionNews.isHidden = true
            }
            else if (segmentIndex == 1) {
                sectionEvents.isHidden = true
                sectionNews.isHidden = false
            }
        }
    }
}

extension EventController: UITableViewDataSource, UITableViewDelegate{
    
    //количество создаваемых клеток
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        if (tableView == self.tableEvents){
            return self.data.count
        }
        else if (tableView == self.tableFinishedEvents) {
            return self.dataFinishedEvents.count
        }
        else if (tableView == self.tableNews) {
            return self.dataNews.count
        }
        else {
            return 0
        }
    }
    
    //создание клетки
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        if (tableView == self.tableEvents){
            let cell = tableEvents.dequeueReusableCell(withIdentifier: idCell) as! MainTableViewCell
            cell.titleEvent.text = self.data[indexPath.row][1]
            cell.descriptionEvent.text = self.data[indexPath.row][2]
            cell.imgEvent.image = UIImage(named: "eventImg")
            cell.timeEvent.text = self.data[indexPath.row][3]
            cell.countPersonsEvent.text = self.data[indexPath.row][4]
            
            return cell
        }
        else if (tableView == self.tableFinishedEvents) {
            let cell = tableFinishedEvents.dequeueReusableCell(withIdentifier: idCell) as! MainTableViewCell
            cell.titleEvent.text = self.dataFinishedEvents[indexPath.row][1]
            cell.descriptionEvent.text = self.dataFinishedEvents[indexPath.row][2]
            cell.imgEvent.image = UIImage(named: "eventImg")
            cell.timeEvent.text = self.dataFinishedEvents[indexPath.row][3]
            cell.countPersonsEvent.text = self.dataFinishedEvents[indexPath.row][4]
            cell.ifUserReg.isHidden = true
            
            return cell
        } else {
            let cell = tableNews.dequeueReusableCell(withIdentifier: idCell) as! MainTableViewCell
            cell.titleEvent.text = self.dataNews[indexPath.row][1]
            cell.descriptionEvent.text = self.dataNews[indexPath.row][2]
            cell.imgEvent.image = UIImage(named: "eventImg")
            cell.timeEvent.isHidden = true
            cell.countPersonsEvent.isHidden = true
            cell.ifUserReg.isHidden = true
            cell.imgTime.isHidden = true
            cell.imgCountPersons.isHidden = true
            
            return cell
        }
        
    }
    //высота клетки
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return 313.0
    }
    //вывод индекса выбранной строки
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        print("\(indexPath.row)")
        if (tableView == self.tableEvents){
            performSegue(withIdentifier: "showdetail", sender: self)
        }
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if let destination = segue.destination as? DetailVC {
            destination.chooseCell = data[(tableEvents.indexPathForSelectedRow?.row)!]
        }
    }
}
