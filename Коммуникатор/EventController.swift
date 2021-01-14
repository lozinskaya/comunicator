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
    var allData : [String: AnyObject] = [:]
    var data : [[String]] = []
    
    //прошедшие мероприятия
    var dataFinishedEvents : [[String]] = []
    let idCell = "MailCell"
    //Количество мероприятий на которые записан пользователь
    @IBOutlet weak var sectionEvents: UIView!
    @IBOutlet weak var sectionNews: UIView!
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
    @IBOutlet weak var countRegisterEvents: UILabel!
    @IBOutlet weak var tableEvents: UITableView!
    @IBOutlet weak var tableFinishedEvents: UITableView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        WebFuncs.Events() { result in
            if let data = result {
                DispatchQueue.main.async {
                    self.allData = data
                    let data_past = data["past"] as? [[String: String]] ?? []
                    let data_future = data["future"] as? [[String: String]] ?? []
                    let dateFormatter = DateFormatter()
                    dateFormatter.dateFormat = "d MMMM в HH:mm"
                    dateFormatter.locale = Locale(identifier: "ru_RU")
                    for row in data_future {
                        let date = Date(timeIntervalSince1970: Double(row["date_time"] ?? "0") ?? 0)
                        let event_date = dateFormatter.string(from: date)
                        var limit_persons = "∞"
                        if row["limit_persons"] != "0" {
                            limit_persons = "До \(row["limit_persons"] ?? "0") человек"
                        }
                        self.data.append([row["id"] ?? "", row["title"] ?? "", row["description"] ?? "", event_date, limit_persons, row["image_url"] ?? ""])
                    }
                        
                    for row in data_past {
                        let date = Date(timeIntervalSince1970: Double(row["date_time"] ?? "0") ?? 0)
                        let event_date = dateFormatter.string(from: date)
                        var limit_persons = "∞"
                        if row["limit_persons"] != "0" {
                            limit_persons = "До \(row["limit_persons"] ?? "0") человек"
                        }
                        self.dataFinishedEvents.append([row["id"] ?? "", row["title"] ?? "", row["description"] ?? "", event_date, limit_persons, row["image_url"] ?? ""])
                    }
                    
                    
                    self.tableEvents.dataSource = self
                    self.tableEvents.delegate = self
                
                    self.tableFinishedEvents.dataSource = self
                    self.tableFinishedEvents.delegate = self
                
                    self.tableEvents.reloadData()
                    self.tableFinishedEvents.reloadData()

                    self.tableEvents.register(UINib(nibName: "MainTableViewCell", bundle: nil ), forCellReuseIdentifier: self.idCell)
                    self.tableFinishedEvents.register(UINib(nibName: "MainTableViewCell", bundle: nil ), forCellReuseIdentifier: self.idCell)
                }
            }
        }
        titleFutureEvents.setTitleColor(UIColor(named: "inputCode"), for: .normal)
        titleFinishedEvents.setTitleColor(UIColor(named: "events"), for: .normal)
        self.navigationController?.navigationBar.setValue(true, forKey: "hidesShadow")
        self.navigationItem.backBarButtonItem = UIBarButtonItem(title: "", style: .plain, target: nil, action: nil)
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
        } else {
            return 0
        }
    }
    
    //создание клетки
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell = tableFinishedEvents.dequeueReusableCell(withIdentifier: idCell) as! MainTableViewCell
        var dataArray = self.dataFinishedEvents
        
        if (tableView == self.tableEvents){
            cell = tableEvents.dequeueReusableCell(withIdentifier: idCell) as! MainTableViewCell
            dataArray = self.data;
        }
        
        cell.titleEvent.text = dataArray[indexPath.row][1]
        cell.descriptionEvent.text = dataArray[indexPath.row][2]

        cell.timeEvent.text = dataArray[indexPath.row][3]
        cell.countPersonsEvent.text = dataArray[indexPath.row][4]
        cell.ifUserReg.isHidden = true
        
        cell.imgEvent.image = UIImage(named: "eventImg")
        let image_url = dataArray[indexPath.row][5]
        if image_url != "" {
            cell.imgEvent.load(url: image_url.getCleanedURL()!)
        }
        
        return cell
        
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
